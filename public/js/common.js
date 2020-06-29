(function () {
    var form = document.getElementsByClassName('form-validate')[0];
    if (form) {
        addEvent(form, 'submit', function () {
            var form = document.getElementsByClassName('form-validate')[0],
                inputs = form.getElementsByTagName('input'),
                textarea = form.getElementsByTagName('textarea')[0],
                selects = form.getElementsByTagName('select');

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

            formValidata.successAjax(form, form.getAttribute('ajax-url'));
        });
    }
}());