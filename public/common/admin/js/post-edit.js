$(document).ready(function () {
    $(window).on('ready resize', function () {
        $("#postBodyContent").width($(window).width() - 360);
        $("#titleInput").width($(window).width() - 390);
    });

    $("#categoryInput").kendoDropDownList({
        dataTextField: "title",
        dataValueField: "ID",
        dataSource: {
            transport: {
                read: {
                    url: "http://localhost/blx/public/prototype/data/categories.json",
                    dataType: "json"
                }
            },
            schema: {
                data: "categories"
            }
        }
    });

    $("#statusInput").kendoDropDownList({
        dataTextField: "text",
        dataValueField: "value",
        dataSource: [
            { text: "Xuất bản", value: "published" },
            { text: "Chờ xem lại", value: "pending_review" },
            { text: "Bản nháp", value: "draft" }
        ]
    });

//    Editor
    $("#ckEditorContainer").ckeditor({
        toolbarGroups: [
            { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
            { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ] },
            { name: 'links' },
            { name: 'insert' },
            { name: 'forms' },
            { name: 'others' },
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
            { name: 'styles' },
            { name: 'colors' },
            '/',
            { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
            { name: 'tools' }
        ],
        language: 'vi',
        removeButtons: 'Underline,Subscript,Superscript',
        format_tags: 'p;h1;h2;h3;pre',
        removeDialogTabs: 'image:advanced;link:advanced'
    });

});