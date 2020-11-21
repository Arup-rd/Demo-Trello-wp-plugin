<template>
    <div class="my-plugin-app">
        <div class="my-plugin-nav">
            <el-menu
                :default-active="activeIndex"
                class="el-menu-demo"
                mode="horizontal"
                @select="handleSelect"
            >
                <span class="plugin_name">{{ $t('My Plugin') }}</span>
                <el-menu-item
                    v-for="item in menu_items"
                    :index="item.route"
                    :key="item.route"
                >{{ $t(item.title) }}
                </el-menu-item>
            </el-menu>
        </div>

        <router-view :key="$route.fullPath"></router-view>
    </div>
</template>

<script type="text/babel">
    export default {
        name: 'MyPluginApplication',
        data() {
            return {
                currentNav: this.$route.meta.navigation || 'data',
                menu_items: [
                    {
                        route: 'Board',
                        title: 'Board'
                    },
                    {
                        route: 'Help',
                        title: 'Help'
                    }
                ],
                activeIndex: 'Tasks'
            };
        },
        methods: {
            handleSelect(key, keyPath) {
                if (this.$route.name !== key) {
                    this.$router.push({ name: key });
                }
            }
        },
        watch: {
            $route(to, from) {
                this.activeIndex = to.name;
            }
        },
        mounted() {
            this.activeIndex = this.$route.name;
        }
    };
</script>

<style lang="scss" scoped>
    .my-plugin-app {
        .plugin_name {
            opacity: 1;
            font-size: 17px;
            color: #6e6e6e;
            outline: none;
            margin: 22px 20px 20px 22px;
            float: left;
        }

        .my-plugin-nav {
            display: block;
            width: 100%;
            overflow: hidden;
            background: white;
            margin-left: -22px;
            padding-right: 40px;
            margin-bottom: 20px;
        }

        .el-menu.el-menu--horizontal {
            border-bottom: none;
        }
    }
</style>
