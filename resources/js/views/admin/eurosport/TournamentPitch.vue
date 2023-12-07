<template>
    <div class="tab-content">
        <div>
        Hello Tournament Pitch
        <a href="javascript:void(0)" @click="savePitchDetail()" >Save Pitch</a>
        </div>
    </div>
</template>

<script type="text/babel">
export default {
	methods: {
		savePitchDetail() {
			let pitchData = {
				'number': '123',
				'type' : 'Grass',
				'location' : 'Location1',
				'Size' : '5-a-side'
				}
			return axios.post('/api/pitch/create',pitchData).then(response =>  {

            }).catch(error => {
                if (error.response.status == 401) {
                    toastr['error']('Invalid Credentials', 'Error');
                } else {
                    // Something happened in setting up the request that triggered an Error
                }
            });
		}
	}
}
</script>
