Vue.component('cmheader', {
    template:
    `
        <div class="navbar navbar-inverse">
            <div class="container">
                <div class="navbar-header">
                    <a href="../index.html" class="navbar-brand">AHNU</a>
                </div>
                <label id="toggle-label" class="visible-xs-inline-block" for="toggle-checkbox">MENU</label>
                <div class="hidden-xs">
                    <ul class="nav navbar-nav">
                        <li id="=goto-index"><a href="#">首页</a></li>
                        <li id="=goto-borrow"><a href="#">借书</a></li>
                        <li id="=goto-return"><a href="#">还书</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li id="goto-login"><a href="../account/login.html">登录</a></li>
                        <li id="goto-register"><a href="../account/register.html">注册</a></li>
                    </ul>
                </div>
            </div>
        </div>

    `,
})

new Vue({
    el: '#header',    
})