

//include(cmf-0.1.js);
cmf.form = new (function () {
    var t = this;

    /* error */
    t.error = new (function () {
        this.view = function (id, errorId, error) {
            if (cmf.getId(errorId)) {
                if (error) {
                    $('#' + errorId).html(error).show();
                } else {
                    $('#' + errorId).hide();
                }
            } else {
                if (!cmf.getId(id))
                    return;
                var parent = $('#' + id).parent();
                if (error) {
                    if (!$('.formError', parent).get(0)) {
                        parent.prepend('<div class="formError">' + error + '</div>')
                    } else {
                        $('.formError', parent).html(error);
                    }
                } else {
                    $('.formError', parent).remove();
                }
            }
        };
        this.setValue = function (id, value) {
            cmf.setValue(id, value);
        };
        this._color = new Array();
        this.color = function (id, error, color) {
            if (!cmf.getId(id))
                return;
            if (this._color[id] == undefined) {
                this._color[id] = new Array();
                this._color[id]['color'] = cmf.getId(id).style.backgroundColor;
                cmf.getId(id).onfocus2 = cmf.getId(id).onfocus;
                cmf.getId(id).onfocus = function (event) {
                    this.style.backgroundColor = cmf.form.error._color[id]['color'];
                    if (cmf.getId(id).onfocus2 != undefined) {
                        cmf.getId(id).onfocus2();
                    }
                }
            }
            if (error) {
                cmf.getId(id).style.backgroundColor = color;
            }
        };
    });


    /* select */
    t.select = new (function () {
        //���������� ������� ������ �������
        this.newSelect = function (id) {
            var selectObj = cmf.getId(id);
            cmf.form.select.childDelete(selectObj);
            return selectObj;
        };

        this.childDelete = function (selectObj) {
            while (selectObj.childNodes.length) {
                if (selectObj.firstChild.tagName == 'optgroup') {
                    while (selectObj.firstChild.childNodes.length)
                        selectObj.firstChild.removeChild(selectObj.firstChild.firstChild);
                }
                selectObj.removeChild(selectObj.firstChild);
            }
        };

        this.option = function (parent, text, value, selected) {
            $(parent).append('<option value="' + value + '" ' + (selected ? 'selected=""' : '') + '>' + text + '</option>');
            return parent.lastChild;
        };

        this.optgroup = function (parent, text, value, selected, selected2) {
            $(parent).append('<optgroup label="' + text + '"></optgroup>');
            return parent.lastChild;
        };
    });


    /* text */
    t.text = new (function () {
        this.selectLabel = function (id) {
            cmf.getId(id).focus();
            cmf.getId(id).select();
        };
        this.onFocus = function (id, value) {
            if (id.value == value)
                id.value = '';
        };
        this.onBlur = function (id, value) {
            if (id.value == '')
                id.value = value;
        };
    });


    /* checkbox */
    t.checkbox = new (function () {
        this.prefix = function (form, prefix, value) {
            var elements = form.elements;
            for (i = 0; i < elements.length; i++)
                if (elements[i].id.indexOf(prefix) != -1)
                    elements[i].checked = value;
        };
        this.select = function (id, checked) {
            if (cmf.getId(id))
                cmf.getId(id).checked = checked;
        };
    });


    /* radio */
    t.radio = new (function () {
        this.select = function (id, checked) {
            if (cmf.getId(id))
                cmf.getId(id).checked = checked;
        };
    });

    /* radio */
    t.images = new (function () {
        var t = this;
        t.init = function (list) {
            t.list = list;
            t.add();
            t.command.init();
        };

        t.images = function (select) {
            return $(select ? '.images-board ' + select : '.images-board', t.list);
        };

        t.add = function () {
            var item = t.images(':first').clone().appendTo(t.list).show();
            var name = item.find('input').attr('name').replace('{key}', t.images().length);
            item.find('input').attr({name: name}).attr({id: name});
            var id = item.find('.formErrorImage').attr('id').replace('{key}', t.images().length);
            item.find('.formErrorImage').attr({id: id});
            item.find('.images-click span:first').click(function () {
                $(this).closest('.images-board').remove();
                t.show();
            });
            item.find('.images-click span:last').click(function () {
                t.add();
            });
            t.show();
        };

        t.show = function () {
            if (t.images().length == 2) {
                t.images().each(function () {
                    $('.images-click span:first', this).hide();
                });
            } else if (t.images().length > 2) {
                t.images('.images-click span').show();
            }
        };

        t.command = new (function () {
            var command = this;
            command.init = function () {
                $('.images-view>span .images-view-command').click(function () {
                    if (!confirm('Удалить?'))
                        return;
                    cmf.ajax.send('/controller/board/delete/?' + Math.random(), {board: $(this).data('board'), key: $(this).data('key')});
                });
            };
        });

    });

});