// 这部分应该可以动态生成，简化代码
// 但是我还没有研究透
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
                    <ul id="nav-left" class="nav navbar-nav">
                        <li id="goto-index"><a href="../index.html">首页</a></li>
                        <li id="goto-borrow"><a href="#">借书</a></li>
                        <li id="goto-return"><a href="#">还书</a></li>
                    </ul>
                    <ul id="nav-right-nologin" class="nav navbar-nav navbar-right">
                        <li id="goto-login"><a href="../account/login.html">登录</a></li>
                        <li id="goto-register"><a href="../account/register.html">注册</a></li>
                    </ul>
                    <ul id="nav-right-login" class="nav navbar-nav navbar-right hide">
                        <li id="info"></li>
                    </ul>
                </div>
            </div>
        </div>
    `,
})

Vue.component('indexheader', {
    template:
    `
        <div class="navbar navbar-inverse">
            <div class="container">
                <div class="navbar-header">
                    <a href="index.html" class="navbar-brand">AHNU</a>
                </div>
                <label id="toggle-label" class="visible-xs-inline-block" for="toggle-checkbox">MENU</label>
                <div class="hidden-xs">
                    <ul id="nav-left" class="nav navbar-nav">
                        <li id="goto-index"><a href="index.html">首页</a></li>
                        <li id="goto-borrow"><a href="#">借书</a></li>
                        <li id="goto-return"><a href="#">还书</a></li>
                    </ul>
                    <ul id="nav-right-nologin" class="nav navbar-nav navbar-right">
                        <li id="goto-login"><a href="account/login.html">登录</a></li>
                        <li id="goto-register"><a href="account/register.html">注册</a></li>
                    </ul>
                    <ul id="nav-right-login" class="nav navbar-nav navbar-right dropdown hide">
                        <li id="info" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"></li>
                        <ul id="account-menu" class="dropdown-menu text-right">
                            <li><a href="#">历史纪录</a></li>
                            <li><a href="#">个人信息</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a id="logout" href="#">退出</a></li>
                        </ul>
                    </ul>
                </div>
            </div>
        </div>
    `,
})

new Vue({
    el: '#header',
})