<template>
	<div class="main-body">
		<div class="page-header">
            <div class="page-container">
                <div class="left-area">
                    <div class="date">{{ currentCategoryName }}</div>
                </div>
                <div class="middle-area">
                    <!-- <div class="title">Matches</div> -->
                </div>
                <div class="right-area">
                    <div>
                        <span class="active-page">{{ currentPage + 1 }}</span> of {{ totalPages }}
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <div class="group-layout grid-group-layout">
                <div class="group-grid-item" v-for="groupStanding in currentPageInformation.records">
                    <div class="group-table-info">
                        <div class="group-table-label">Group</div>
                        <div class="group-table-name">{{ groupStanding.name }}</div>
                    </div>
                    <table class="group-table">
                        <thead>
                            <tr>
                                <th class="teams">Teams</th>
                                <th class="played">Played</th>
                                <th class="difference">Difference</th>
                                <th class="points">Points</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="standing in groupStanding.standings">
                                <td class="teams">
                                    <span class="team-section">
                                        <span class="flag-area">
                                            <span class="flag-icon flag-icon-ax"></span>
                                        </span>
                                        <span class="team-name">{{ standing.name }}</span>
                                    </span>
                                </td>
                                <td class="played">{{ standing.played }}</td>
                                <td class="difference">{{ standing.goal_for - standing.goal_against | formatGoalDifference }}</td>
                                <td class="points">{{ standing.points }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="page-footer"></div>
	</div>
</template>
<script type="text/babel">
	export default {
		props: ['currentPage', 'currentCategoryName', 'totalPages', 'currentPageInformation'],
		data() {
            return {
            	
            }
        },
        computed: {
            
        },
        filters: {
            formatGoalDifference(goalDifference) {
                if(goalDifference > 0)
                    return '+' + goalDifference;
                return goalDifference;
            }
        },
        methods: {
            
        }
	}
</script>