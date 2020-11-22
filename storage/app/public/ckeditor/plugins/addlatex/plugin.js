CKEDITOR.plugins.add('addlatex',{
    init: function(editor){
        var cmd = editor.addCommand('addlatex', {
            exec:function(editor){
                editor.insertHtml( "<div class='math'>сбда формулу</div>" ); // собственно сама работа плагина
            }
        });
        cmd.modes = { wysiwyg : 1, source: 1 };// плагин будет работать и в режиме wysiwyg и в режиме исходного текста
        editor.ui.addButton('addlatex',{
            label: 'Добавить LaTex-формулу',
            command: 'addlatex',
            toolbar: 'about'
        });
    },
    icons:'addlatex', // иконка
});
