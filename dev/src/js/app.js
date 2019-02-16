'use strict';

import "../sass/app.scss";

import 'bootstrap';
import 'waitMe/waitMe';

window.$ = window.jQuery = $;


class CApp {
    constructor() {
        console.log('init app');

        window.App = this;
    }

    generateShortUrlHandler(e, sender) {
        e.preventDefault();

        let $sender = $(sender);
        let loadingClass = 'loading';

        let beforeSend = (xhr) => {
            if ($sender.hasClass(loadingClass)) {
                xhr.abort();
                return false;
            }

            $sender.addClass(loadingClass);
            $sender.waitMe({
                effect: 'bounce',
                text: '',
                bg: 'rgba(242,244,245, .5)',
                color: '#000'
            });

            $("#modal-form").hide();
            $("#modal-copy").hide();
        };

        let success = (response) => {
            console.log(response);

            if (response.result === false) {
                $("#modal-title").text('Error');
            } else {
                $("#modal-title").text('Success');
                $("#modal-input").val(response.shorturl);
                $("#modal-form").show();
                $("#modal-copy").show();

            }

            $("#modal-message").text(response.message);
            $('#modal').modal('show');
        };

        let error = (response) => {
            console.error('FATAL: ' + response.statusText + response.responseText);
        };

        let complete = () => {
            $sender.removeClass(loadingClass);
            $sender.waitMe('hide');
        };

        let data = $sender.serialize();

        $.ajax({
            url: '/ajax/generate',
            type: 'POST',
            dataType: 'json',
            data,
            beforeSend,
            success,
            error,
            complete
        });

    }

    copyToClipboard($input, sender) {
        $input.select();

        document.execCommand("copy");

        $(sender).removeClass("btn-info").addClass("btn-success");
    };

    selectText(sender) {
        $(sender).select();
    }
}

$(window).bind("load.app", () => new CApp());