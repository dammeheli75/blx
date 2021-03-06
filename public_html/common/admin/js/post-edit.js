$(document).ready(function () {
    $(window).on('ready resize', function () {
        $("#postBodyContent").width($(window).width() - 360);
        $("#titleInput").width($(window).width() - 390);
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
        removeDialogTabs: 'image:advanced;link:advanced',
        height: $(window).height() - 300
    });
});