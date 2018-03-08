import axios from 'axios';

export default {
	// Get filter dropdown data
	getFilterDropDownData() {
		return axios.get('/api/getFilterDropDownData');
	},
}
