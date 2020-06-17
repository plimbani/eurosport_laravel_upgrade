<template>
    <div>
        <div class="tournament-logo">
            <div class="logo-container is-left">
                <img v-if="tournament.tournamentLogo != null" :src="tournament.tournamentLogo" alt="Tournament Logo" />
                <img v-else-if="currentLayout === 'tmp'" :src="tmpLogoUrl" alt="Tournament Logo">
                <img v-else-if="currentLayout === 'commercialisation'" src="commercialisationLogoUrl" alt="Tournament Logo">
            </div>
        </div>
        <div class="sidebar-container">
            <div class="sidebar-header">
                <div class="sidebar-title">
                    Age categories
                </div>
            </div>
            <div class="sidebar-body" v-slimscroll>
                <ul class="category-list">
                    <li v-for="(ageCategory, key) in ageCategories" :class="getAgeCategoryClasses(ageCategory.id, currentAgeCategoryId)">
                        <a href="#" class="list-link">{{ ageCategory.group_name + '(' + ageCategory.category_age + ')' }}</a>
                    </li>
                </ul>
            </div>
            <div class="sidebar-footer"></div>
        </div>
    </div>
</template>
<script type="text/babel">
    export default {
        props: ['currentLayout', 'tmpLogoUrl', 'commercialisationLogoUrl', 'tournament', 'ageCategories', 'currentAgeCategoryId'],
        components: {
        },
        data() {
            return {
            }
        },
        watch: {
            currentAgeCategoryId: function(val) {
                $(".sidebar-container .sidebar-body").scrollTop($(".js-age-category-" + val).offset().top);
            }
        },
        methods: {
            getAgeCategoryClasses(ageCategoryId, currentAgeCategoryId) {
                let classes = 'list-item ' + 'js-age-category-' + ageCategoryId;
                if(currentAgeCategoryId === ageCategoryId) {
                    classes += ' active'
                }
                return classes;
            }
        },
    }
</script>