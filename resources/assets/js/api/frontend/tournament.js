import axios from 'axios';

export default {
  // Get competition format
  getCompetitionFormat(data) {
    return api.post('/api/age_group/getCompetationFormat', {'tournamentData': data});
  },
};
