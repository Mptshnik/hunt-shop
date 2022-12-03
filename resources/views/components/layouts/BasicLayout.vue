<template>
    <div>
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <div class="container-fluid">
                    <div class="navbar-brand">HuntShop|Management</div>
                    <div class="collapse navbar-collapse" id="navbarColor02">
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <router-link to="/" class="nav-link active" href="#">Главная
                                    <span class="visually-hidden">(current)</span>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="/posts" class="nav-link" href="#">Должности</router-link>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Pricing</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">About</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Dropdown</a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Separated link</a>
                                </div>
                            </li>
                        </ul>
                        <form class="d-flex">
                            <input class="form-control me-sm-2" type="text" placeholder="Search">
                            <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
                        </form>
                    </div>
                </div>
            </nav>
        </header>
        <div class="mt-5">
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    name: "BasicLayout"
}
</script>

<style scoped>
.navbar-custom
{
    background-color: #313335;
}
footer {
    position: absolute;
    bottom: 0px;
    width: 100%;
    height: 55px;
}
</style>
