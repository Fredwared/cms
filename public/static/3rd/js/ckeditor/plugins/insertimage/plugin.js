/**
 * Insert image
 */
CKEDITOR.plugins.add('insertimage', {
    lang: ['en', 'vi'],
    init: function (editor) {
        var commandName = 'insertimage';
        var commandNameButton = 'InsertImage';

        function addButtonAndItem(definition, execCode, listener) {
            editor.ui.addButton(definition.command_button, definition);

            editor.addCommand(definition.command, {
                exec: execCode
            });

            if (editor.addMenuItem) {
                editor.addMenuItem(definition.command, definition);
            }

            if (editor.contextMenu) {
                editor.contextMenu.addListener(listener);
            }
        }

        editor.addMenuGroup('content');

        addButtonAndItem({
            command: commandName,
            command_button: commandNameButton,
            label: editor.lang.insertimage.title,
            icon: this.path + 'images/icon.png',
            group: 'content'
        }, function (editor) {
            bootbox.dialog({
                title: editor.lang.insertimage.title,
                message: '<div id="filemanager_modal" class="text-center"><i class="fa fa-spinner fa-spin fa-5x"></i></div>',
                backdrop: true,
                animate: true,
                size: 'large'
            });

            $.ajax({
                url: editor.config.insertImageUrl,
                success: function(response) {
                    $('#filemanager_modal').removeClass('text-center').html(response);
                }
            });
        }, function(element, selection) {
            if (!element || element.data('cke-realelement') || element.isReadOnly() || $(element.$).attr('data-component') || $(element.$).parents('.img-wrap').size() > 0) {
                return null;
            }

            return {
                insertimage: CKEDITOR.TRISTATE_OFF
            };
        });
    }
});