/**
 *    main page div section
 *
 * @type {string}
 */
var mainPage = 'main_page_contents';

/**
 *
 *   postJSON
 *
 */
(function ($) {
    $.postJSON = function (url, obj, callback) {
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: JSON.stringify(obj),
            contentType: 'application/json; charset=utf-8',
            success: callback
        });
    };
}(jQuery));


/**
 *
 * @param title
 * @param desc
 * @param url
 * @param img
 *
 *   Meta tags add to page
 */

function social(title, desc, url, img) {
    $(function () {
        $.postJSON('App/Ajax/socialMedia.php', { title: title, desc: desc, url: url, img: img }, function (data, status, xhr) {
        });
    });
}

/**
 *   change Cookie Lang
 *
 * @param iso
 */
function changeLanguage(iso) {
    $(function () {

        $.ajax({
            type: "POST",
            url: "App/Ajax/language.php",
            data: { profile: iso }
        })
            .done(function (msg) {
                location.reload();
            });

    });
}

/**
 *
 *   Send Cookies via ajax
 *
 * (c) 2013 by dsphinx
 *
 * @param cookie
 * @param val
 * @param how
 * @param log
 */
function cookieManager(cookie, val, how, log) {
    log = (log > 0) ? 1 : 0;
    $(function () {
        $.ajax({
            type: "POST",
            url: "App/Ajax/cookieManager.php",
            data: { cookie: cookie, val: val, long: how, log: log}
        })


    });
}

/**
 *
 *    download Manager
 *
 * @param md5file
 * @param filename
 */
function downloadFile(md5file, filename, section) {
    $(function () {
        $.ajax({
            type: "POST",
            //   dataType: 'jsonp',
            url: "App/Ajax/downloadManager.php",
            data: { md5: md5file, name: filename, path: section }
        })
            .done(function (msg) {
                if (msg) {
                    alert(msg);
                }
            });

    });
}

/**
 *
 *  show PDF via mozilla PDF.js
 *
 * @param pdf
 */
function pdfShow(pdfFile) {

    var viewer = 'Contents/showPDF.php?file=';
    // alert( viewer + pdfFile );
     newwindow = window.open(viewer + pdfFile, '', 'height=600,width=850,location=0,menubar=0,status=0');
    if (window.focus) {
        newwindow.focus()
    }


}


function contentShowHtml(htmldata, title) {
     $(function () {

        $('#'+mainPage).html(htmldata);
         document.title = title;

     });

}



function contentShow(url) {
    // Ajax load and changing browser url
    $(function () {

        // fancy view
        $('#'+mainPage).html('<img class="centerBlock"  src="Media/images/loading.gif">');


        // $("a[rel='content']").click(function (e) {
        //  e.preventDefault();

        // if uncomment the above line, html5 nonsupported browers won't change the url but will display the ajax content;
        // if commented, html5 nonsupported browers will reload the page to the specified link.

        //get the link location that was clicked
        pageurl = url;

        //to get the ajax content and display in div with id 'content'
        //
        // 	<a rel='content' href='http://localhost/html5-history-api/menu1.php'>menu1</a>
        //
        $.ajax({url: pageurl, success: function (data) {
            $('#'+mainPage).html(data);
        }});


        var tmp = url.split("?");
        var urlOptions = tmp[1];
        var urlTitle = urlOptions.split("title=");
        var tmp2 = tmp[0].split("/");
        var pageUrlFrame = tmp2[1].replace(".php", '');

        document.title = urlTitle[1];

        pageurl = "?page=" + pageUrlFrame + "&" + urlOptions;


        //to change the browser URL to 'pageurl'
        if (pageurl != window.location) {
            window.history.pushState({path: pageurl}, '', pageurl);
        }
        //   return false;
        // });
        $("#"+mainPage).css("display", "none");
        $("#"+mainPage).fadeIn(300);

        /*
         //    Ajax load and changing browser url --
         // the below code is to override back button to get the ajax content without reload
         //
         $(window).bind('popstate', function () {
         $.ajax({url: location.pathname + '?rel=content', success: function (data) {
         $('#content').html(data);
         }});
         });
         */
    });

}

/**
 *
 *   Scroll to up - top page
 */
function scrollToTop() {
    verticalOffset = typeof(verticalOffset) != 'undefined' ? verticalOffset : 0;
    element = $('body');
    offset = element.offset();
    offsetTop = offset.top;
    $('html, body').animate({scrollTop: offsetTop}, 500, 'linear');
}


$(document).ready(function () {


    $('.tool').tooltip();

    $('#sectionNews').load('App/Ajax/news.php');

    //  $("img.lazy").show().lazyload({
    //      effect : "fadeIn"
    //   });


    $('.scroll-top-wrapper').on('click', scrollToTop);


    /*  scroll to top */
    $(document).on('scroll', function () {
        if ($(window).scrollTop() > 100) {
            $('.scroll-top-wrapper').addClass('show');
        } else {
            $('.scroll-top-wrapper').removeClass('show');
        }
    });

    //  $("#content").css("display", "none");
    //  $("#content").fadeIn(300);

});


