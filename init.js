/*
 *  Copyright (c) Codiad & daeks, distributed
 *  as-is and without warranty under the MIT License. See
 *  [root]/license.txt for more. This information must remain intact.
 */ 
 
 (function (global, $) {

    var codiad = global.codiad,
        scripts= document.getElementsByTagName('script'),
        path = scripts[scripts.length-1].src.split('?')[0],
        curpath = path.split('/').slice(0, -1).join('/')+'/';

    $(window)
        .load(function() {
            codiad.macro.init();
        });

    codiad.macro = {

        controller: curpath + 'controller.php',
        dialog: curpath + 'dialog.php',

        //////////////////////////////////////////////////////////////////
        // Initilization
        //////////////////////////////////////////////////////////////////

        init: function () {
            var _this = this;
            $.get(_this.controller + '?action=init', function(data) {
                var response = jQuery.parseJSON(data);
                jQuery.each(response, function(i, val) {
                  var macro = '<a class="both" onclick="codiad.macro.execute(\''+i+'\',$(\'#context-menu\').attr(\'data-path\'));"><span class="icon-bookmarks"></span>'+val[0]+'</a>';
                  $('#context-menu').append(macro);
                });
            });
        },
        
        //////////////////////////////////////////////////////////////////
        // Config
        //////////////////////////////////////////////////////////////////

        config: function () {
            var _this = this;
            $('#modal-content form')
                .die('submit'); // Prevent form bubbling
                codiad.modal.load(850, this.dialog + '?action=config');
        },
        
        //////////////////////////////////////////////////////////////////
        // Add
        //////////////////////////////////////////////////////////////////

        add: function () {
            var rowid = parseInt($('#i').val())+1;
            var newcommand = '<tr id="l'+rowid+'"><td width="200px"><input id="rowid" type="hidden" value="'+rowid+'"><input class="macro-command" id="n'+rowid+'" type="text" value=""></td><td width="600px"><input class="macro-command" id="c'+rowid+'" type="text" value=""></td><td width="50px"><button class="btn-left" onclick="codiad.macro.remove(\''+rowid+'\',);return false;">X</button></td></tr>';
            $('#macrolist').append(newcommand);
            $('#macrolist').scrollTop(1000000);
            $('#i').val(rowid);
        },
        
        //////////////////////////////////////////////////////////////////
        // Del
        //////////////////////////////////////////////////////////////////

        remove: function (id) {
            $('#l' + id).remove();
        },
        
        //////////////////////////////////////////////////////////////////
        // Save
        //////////////////////////////////////////////////////////////////

        save: function () {
            var _this = this;
            var formData = {'name[]' : [], 'command[]' : []};
            
            $("#macrolist tr").each(function(i, tr) {
                $this = $(this)
                var rowid = $this.find("input#rowid").val();
                formData['name[]'].push($this.find("input#n"+rowid).val());
                formData['command[]'].push($this.find("input#c"+rowid).val());
            });
            
            $.get(this.controller+'?action=save', formData, function(data){
                var response = codiad.jsend.parse(data);
                if (response != 'error') {
                    window.location.reload();
                } else {
                    codiad.message.error('Save failed');
                }
            });
        },
        
        //////////////////////////////////////////////////////////////////
        // Save
        //////////////////////////////////////////////////////////////////

        execute: function (id, path) {
            var _this = this;
            $('#modal-content form')
                .die('submit'); // Prevent form bubbling
                codiad.modal.load(850, this.dialog + '?action=execute&id=' + id + '&path=' + path);
        }

    };

})(this, jQuery);