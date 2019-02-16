<?php

class Shorturl extends DB
{

    private $url, $hash, $_attempt = 0;

    const HASH_LENGTH  = 10; //hash length
    const MAX_ATTEMPTS = 5; //max attempts for hash generating

    //массив "плохих" хостов. Для них запрещено создавать шоты
    const BAD_HOSTS = [
        "google.com",
        "stackoverflow.com",
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    public function add()
    {
        $resValid = $this->validateUrl();
        if ($resValid !== true) {
            return ['result' => false, 'message' => $resValid];
        }

        //количество попыток исчерпано.
        //Если это случилось, пожалуй, стоит увеличить значение HASH_LENGTH
        if ($this->_attempt >= self::MAX_ATTEMPTS) {
            return ['result' => false, 'message' => 'Error. Please try again'];
        }

        $this->_attempt++;
        $this->generateHash();

        //если сгенерированный хэш уже есть в БД, то делаем еще одну попытку
        //количество попыток ограничено MAX_ATTEMPTS
        if ($this->find()) {
            return $this->add();
        }

        $resSave = $this->save();

        return $resSave === true
            ? [
                'result'   => true,
                'message'  => 'This is your short URL:',
                'shorturl' => Common::getServerUri() . $this->hash
            ]
            : [
                'result'  => false,
                'message' => 'Error #2. Please try again'
            ];
    }

    /**
     * Ищет запись в БД по хэшу
     * @return null|string
     */
    public function find()
    {
        $sQuery = "SELECT url FROM hashes WHERE hash = ? ";
        $obQuery = self::$db->prepare($sQuery);
        $obQuery->execute([$this->hash]);
        $arResult = $obQuery->fetch(PDO::FETCH_ASSOC);

        return $arResult["url"];
    }

    /**
     * Проверяет url на валидность
     * @return bool|string
     */
    private function validateUrl()
    {
        if (!filter_var($this->url, FILTER_VALIDATE_URL) &&
            !filter_var(idn_to_ascii($this->url), FILTER_VALIDATE_URL)) {
            return 'Invalid URL';
        }

        $host = parse_url($this->url, PHP_URL_HOST);
        $arBadHosts = self::BAD_HOSTS;
        array_push($arBadHosts, $_SERVER["HTTP_HOST"]);

        foreach ($arBadHosts as $badHost) {
            if (stripos($host, $badHost) !== FALSE) {
                return 'Bad host';
            }
        }

        return true;
    }

    /**
     * Генеририует случайный хэш
     */
    private function generateHash()
    {
        $hash = sha1(uniqid(time() . mt_rand(), true));
        $hash = strtolower(substr($hash, 0, self::HASH_LENGTH));

        $this->setHash($hash);
    }

    /**
     * Записывает в БД
     * @return bool true в случае успеха, false - в случае неудачи
     */
    private function save()
    {
        $sQuery = "INSERT INTO hashes SET url = ? , hash = ? ";

        $sth = self::$db->prepare($sQuery);
        $sth->execute([$this->url, $this->hash]);

        return empty($sth->last_error);
    }
}