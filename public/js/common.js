(function () {
    var form = document.getElementsByClassName('form-validate');
    for (var i = 0; i < form.length; i++) {
        if (form[i]) {
            addEvent(form[i], 'submit', function () {
                var inputs = this.getElementsByTagName('input'),
                    textarea = this.getElementsByTagName('textarea')[0],
                    selects = this.getElementsByTagName('select');

                for (var i = 0; i < selects.length; i++) {
                    var selectsClassList = [].slice.call(selects[i].classList, 0);
                    
                    if (selectsClassList.indexOf('ignore') != 0 && selects[i].value == 0) {
                        layer.msg('请选择下拉框', {icon: 2});
                        return;
                    }
                }

                for (var i = 0; i < inputs.length; i++) {
                    // 忽略不验证的input
                    var classList = [].slice.call(inputs[i].classList, 0);
                    if (inputs[i].getAttribute("type") == "file") {
                        continue;
                    }else if(inputs[i].getAttribute("type") == "hidden"){
                        continue;
                    }else if( (classList.indexOf('ck') > -1) ){
                        continue;
                    }
                    
                    // 检验是否为空
                    if (formValidata.isEmpty(inputs[i], inputs[i].value)) {
                        return;
                    }
                    if (inputs[i].getAttribute("type") == "text") {}
                }

                if (textarea) {
                    if (textarea.value === '' || textarea.value == null) {
                        layer.msg('请输入文章内容', {icon: 2});
                        return;
                    }
                }

                formValidata.successAjax(this, this.getAttribute('ajax-url'));
            });
        }
    }

    // 操作文件
    var file_group = document.getElementsByClassName('file-btn')[0];
    addEvent(file_group, 'click', function(e){
        var event = e || window.event,
            target = event.target || event.srcElement,
            tag_name = target.tagName.toLowerCase();

        if (tag_name == 'span') {
            target = retParent(target, 1);
        }
        
        var data = {
            "act": target.dataset.act,
            "path": target.dataset.path,
            "file_name": target.dataset.fileName,
        },
        url = '/file.php';

        switch (data.act) {
            case 'show_content':
                return loadData({
                    url: url,
                    success: function (result) {
                        if (result.code == 0) {
                            var file_content = document.getElementsByClassName('file-content')[0];
                            file_content.innerHTML = result.data;
                            $('#show-content').modal('show');
                        }else{
                            layer.msg(result.msg, {icon: 2});
                        }
                    },
                    params: JSON.stringify(data)
                });
            case 'edit_content':
                return loadData({
                    url: url,
                    success: function (result) {
                        if (result.code == 0) {
                            var file_edit = document.getElementsByClassName('file-edit')[0],
                                file_name = document.getElementsByName('file_name')[1];
                            file_edit.value = result.data;
                            file_name.value = data.file_name;
                            $('#edit-content').modal('show');
                        }else{
                            layer.msg(result.msg, {icon: 2});
                        }
                    },
                    params: JSON.stringify(data)
                });
            case 'rename_file':
                var new_file_name = document.getElementsByName('new_file_name')[0],
                file_name = document.getElementsByName('file_name')[2];
                new_file_name.value = '';
                file_name.value = data.file_name;
                $('#rename-file').modal('show');
                break;
            case 'del_file':
                var file_name = document.getElementsByName('file_name')[3];
                file_name.value = data.file_name;
                $('#del-file').modal('show');
                break;
            case 'file_path':
                return loadData({
                    url: '/index.php',
                    success: function (result) {
                        // if (result.code == 0) {
                        
                        // }else{
                        //     layer.msg(result.msg, {icon: 2});
                        // }
                    },
                    params: JSON.stringify(data)
                });
                break;
            
        }
        
    });
    
}());