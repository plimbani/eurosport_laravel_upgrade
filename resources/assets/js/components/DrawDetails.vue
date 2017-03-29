<template>
<div>
<h3>{{otherData.DrawName}}</h3>


<table class="table draw_table">
	<thead>
        <tr>
            <th></th>
            <th></th>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
        </tr>
    </thead>
    <tbody>
    	<tr>
    		<td>1</td>
    		<td> 
    			<a @click="teamDetails('')" href=""> 
    			  <img src="/assets/img/flag.png" width="20"> &nbsp;
    			  <span>SV Heimstetten U12 </span>
    			</a>
    		</td>
    		<td></td>
    		<td>2-0 Won</td>
    		<td>6-1 Won</td>
    		<td>1-0 Won</td>
    	</tr>

    	<tr>
    		<td>2</td>
    		<td> 
    			<a @click="teamDetails('')" href=""> 
    			  <img src="/assets/img/flag.png" width="20"> &nbsp;
    			  <span>CVC Reujwik 1 </span>
    			</a>
    		</td>
    		<td>0-2 Lost</td>
    		<td></td>
    		<td>1-0 Won</td>
    		<td>3-0 Won</td>
    	</tr>
    </tbody>
</table>
<h4> Matches</h4>
<matchList :matchData="matchData"></matchList>
</div>
</template>
<script type="text/babel">
import MatchListing from './MatchListing.vue'
import MatchList from './MatchList.vue'
import LocationList from'./LocationList.vue'

export default {
	props: ['matchData','otherData'],
	mounted() {
		// here we call function to get all the Draws Listing
		//this.getAllDraws()
	},
	components: {
        MatchList,LocationList,MatchListing
	}
}
</script>