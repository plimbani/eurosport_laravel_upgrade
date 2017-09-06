import * as types from '../mutation-types'
import _ from 'lodash'
import Tournament from '../../api/tournament'
import moment from 'moment'

// initial state
const state = {
  tournamentName: '',
  tournamentStartDate:"",
  tournamentEndDate:"",
  tournamentId: '',
  currentTemplate: '',
  currentTotalTime: '',
  tournamentDays: '',
  venues: [],
  tournamentStatus: '',
  tournamentLogo: '',
  facebook:'facebook',
  twitter:'twitter',
  website:'website',
  tournamentFiler:{
  	filterKey:'',
  	filterValue: ''
  },
  totalMatch:'',
  totalReferee:'',
  referees:[],
  competationList:{},
  matches:[],
  matchCompetition:{'matchList':''},
  competitionWithGames: {},
  tournamentStages: {},
  scheduledMatches: [],
}
// getters
const getters = {
   getAllReferees: state => {
      return state.referees
    },
    getTournamentStages: state => {
      return state.tournamentStages
    },
    getCompetitionList: state => {
       return state.competationList
    },
    getAllCompetitionWithGames: function(state) {
      return state.competitionWithGames
    },
    getMatches: state => {
      return state.matches
    },
    totalMatch: state => {
      return state.totalMatch
    },
    scheduledMatches: state => {
      return state.scheduledMatches
    }

}
// actions
const actions = {
  SetTournamentTotalTime({commit}, totalTime) {
	commit(types.SET_TOURNAMENT_TOTAL_TIME, totalTime)
  },
  setTournamentStatus({commit}, status) {
	commit(types.SET_TOURNAMENT_STATUS, status)
  },
  SetTournamentName ({commit}, tournamentData) {
	commit(types.CURRENT_TOURNAMENT, tournamentData)
  },
  SetTotalMatch ({commit}, totalMatch) {
	commit(types.TOTAL_MATCHES, totalMatch)
  },
  SetTotalReferee ({commit}, totalReferee) {
	commit(types.TOTAL_REFEREES, totalReferee)
  },
  setCompetationList({commit}, competition) {
  commit(types.COMPETITION_LIST, competition)
  },

  setTournamentStages({commit}, tournamentStages) {
  commit(types.TOURNAMENT_STAGES, tournamentStages)
  },
  setMatches({commit,state}) {
      commit(types.SET_MATCHES, '')
    let tdata = {}
    if(state.tournamentFiler.filterKey != '' && state.tournamentFiler.filterValue != '') {
        tdata ={'tournamentId':state.tournamentId ,'filterKey':state.tournamentFiler.filterKey,'filterValue':state.tournamentFiler.filterValue.id,'fiterEnable':true}
    } else {
        tdata ={'tournamentId':state.tournamentId }
    }
    // console.log(state.tournamentId)
    Tournament.getFixtures(tdata).then(
    (response)=> {
      // console.log(response.data.data)
      // this.matches = response.data.data
      // console.log('msg')
      // this.$store.dispatch('setMatches',response.data.data)
       commit(types.SET_MATCHES, response.data.data)
       commit(types.SET_COMPETITION_WITH_GAMES)
     
    }
  )
    
  },
  
  

  SetVenues ({commit},tournamentId) {
	Tournament.getAllVenues(tournamentId).then (
	  (response) => {
		commit(types.SET_VENUES, response.data.data)
	  },
	  (error) => {
		console.log('Error occured during Get pitches detail ', error)
	  }
	)
  },
  SetTemplate ({commit}, tournamentData) {
	Tournament.getTemplate(tournamentData).then (
	  (response) => {
		let TournamentRespData = {'json': response.data.data , 'TotalTime': tournamentData.totalTime}
		commit(types.SET_TEMPLATE, TournamentRespData)
	  },
	  (error) => {
		console.log('Error occured during Set Template Fomat api ', error)
	  }
	)
  },
  SaveCompeationFormatDetails  ({commit}, competationFormatData) {

	Tournament.saveCompetationFormat(competationFormatData).then(

	  (response) => {
		if(response.data.status_code == 200) {
			// Now here we set the template
			// let data1 = {'id':response.data.data,'name':tournamentData.name}
			commit(types.SAVE_COMPETATION_FORMAT, competationFormatData)
		} else {
		  alert('Error Occured')
		}
		// commit(types.SAVE_TOURNAMENT, response.data)
	  },
	  (error) => {
		console.log('Error occured during Save Compeation Fomat api ', error)
	  }
	)
  },
  SaveTournamentDetails ({commit}, tournamentData) {
	Tournament.saveTournament(tournamentData).then(

	  (response) => {

		if(response.data.status_code == 200) {
		  // Now here we set the tournament Id and Name
		  //let data1 = {'tournamentData':response.data.data}

		  commit(types.CURRENT_TOURNAMENT, response.data.data)
		} else {
		  alert('Error Occured')
		}
		// commit(types.SAVE_TOURNAMENT, response.data)
	  },
	  (error) => {
		console.log('Error occured during SaveTournament api ', error)
	  }
	)

  },
  setTournamentFilter({commit}, filterData) {
  	commit(types.SET_TOURNAMENT_FILTER, filterData)
  },
  getAllReferee({commit}, tournamentId){
  	// let referees = ''
    let tournamentData = {
        'tournamentId':tournamentId,
        'age_category':''
      }
       commit(types.SET_REFEREES,'')
  	 Tournament.getReferees(tournamentData).then(
      (response) => {
        if(response.data.referees){
          // vm.referees = response.data.referees
          // vm.$store.dispatch('SetTotalReferee', response.data.referees.length)
          commit(types.TOTAL_REFEREES, response.data.referees.length)
           commit(types.SET_REFEREES, response.data.referees)
        }else{
          // vm.referees = ''
          // vm.$store.dispatch('SetTotalReferee', 0)
        }

      },
      (error) => {
         console.log('Error occured during Tournament api ', error)
      }
    )
  },
  setCompetationWithGames({commit}) {
    commit(types.SET_COMPETITION_WITH_GAMES)
  },
  SetScheduledMatches({commit,state}) {
    let tournamentData= []
    if(state.tournamentFiler.filterKey != '' && state.tournamentFiler.filterValue != '') {
        tournamentData ={'tournamentId':state.tournamentId ,'filterKey':state.tournamentFiler.filterKey,'filterValue':state.tournamentFiler.filterValue.id}
    } else {
        tournamentData ={'tournamentId':state.tournamentId }
    }
    // let tournamentData ={'tournamentId':this.tournamentId }
    // Tournament.getFixtures(tournamentData).then(
    //     (response)=> {
    //         let vm = this
    //         let counter =999;
    //         let rdata = response.data.data
    //         // this.reports = response.data.data
    //         // console.log(rdata)
    //         let sMatches = []

    //         _.forEach(rdata, function(match) {
    //             let scheduleBlock = false
    //             let refereeId = ''
    //             let matchTitle = ''

    //             let mtchNumber = match.match_number
    //             let mtchNumber1 = mtchNumber.split(".")
    //             let mtchNum = mtchNumber1[0]+'.'+mtchNumber1[1]

    //             let lastElm = mtchNumber1[2]
    //             let teams = lastElm.split("-")

    //             let Placehometeam =  teams[0]
    //             let Placeawayteam =  teams[1]

    //             if(match.Home_id != 0){
    //             Placehometeam = match.HomeTeam
    //             }
    //             if(match.Away_id != 0){
    //             Placeawayteam = match.AwayTeam
    //             }
    //             let mtc = ''
    //             mtc = mtchNum+'.'+Placehometeam+'-'+Placeawayteam
    //             //console.log(mtc)
    //             match.match_number = mtc
    //             if(match.is_scheduled == 1){
    //                 if(state.tournamentFiler.filterKey == 'age_category'){
    //                     if( state.tournamentFiler.filterValue != '' && state.tournamentFiler.filterValue.id != match.tid){
    //                         scheduleBlock = true
    //                     }
    //                 }else if(state.tournamentFiler.filterKey == 'location'){
    //                     if( state.tournamentFiler.filterValue != '' && state.tournamentFiler.filterValue.id != match.venueId){
    //                         scheduleBlock = true
    //                     }
    //                 }
    //               // console.log('match is'+JSON.stringify(match))
    //               let colorVal = (match.homeScore == null && match.AwayScore == null) ? '#2196F3' : 'green'
    //               if(scheduleBlock){
    //                 colorVal = 'grey'
    //               }
    //               let lastName = match.last_name
    //               let firstName = match.first_name
    //               // console.log(lastName,firstName)
    //               let refereeName = ''
    //               if(lastName != null && firstName!= null){
    //                 //refereeName = lastName.substr(0,1)+firstName.substr(0,1)
    //                 refereeName = firstName+ ' '+lastName
    //               }
    //               if(scheduleBlock){
    //                 refereeId = -1
    //                 matchTitle = 'Match scheduled - '+match.match_number
    //               }else{
    //                 refereeId = match.referee_id?match.referee_id:0
    //                  matchTitle = match.match_number
    //               }
    //              // console.log(val)
    //                 let mData =  {
    //                     'id': match.fid,
    //                     'resourceId': match.pitchId,
    //                     'start':moment.utc(match.match_datetime,'YYYY-MM-DD HH:mm:ss'),
    //                     'end': moment.utc(match.match_endtime,'YYYY-MM-DD HH:mm:ss'),
    //                     'refereeId': refereeId,
    //                     'refereeText': refereeName,
    //                     'title':matchTitle,
    //                     'color': colorVal,
    //                     'matchId':match.fid,
    //                     'matchAgeGroupId':match.age_group_id
    //                 }
    //                // console.log('match typeof')
    //                // console.log(typeof match.homeScore)
    //               //  console.log(mData)
    //             sMatches.push(mData)
    //             }
    //         });
    //         let scheduledSmatches = sMatches;
    //        let scheduledMatchesByStage = []
    //        let minTimePitchAvail = []
    //         let maxTimePitchAvail = []
    //         let sPitch = [] 
    //         _.forEach(state.tournamentStages[0].pitches, (pitch) => {
            
    //         // let sMatches = scheduledSmatches 
    //               _.forEach(pitch.pitch_availability, (availability) => {
    //                 console.log('msg')
    //                   if(availability.stage_start_time != '08:00:00' ){
    //                       minTimePitchAvail.push(moment.utc(availability.stage_start_date+' '+availability.stage_start_time,'DD/MM/YYYY hh:mm:ss'))
    //                   }
    //                   if(availability.stage_start_time != '19:00:00' ){
    //                       maxTimePitchAvail.push(moment.utc(availability.stage_start_date+' '+availability.stage_end_time,'DD/MM/YYYY hh:mm:ss'))
    //                   }
    //                   let mData = {
    //                       'id': counter,
    //                       'resourceId': pitch.id,
    //                       'start':moment(availability.stage_start_date+' '+availability.break_start_time,'DD/MM/YYYY hh:mm:ss'),
    //                       'end': moment.utc(availability.stage_start_date+' '+availability.break_end_time,'DD/MM/YYYY hh:mm:ss'),
    //                       'refereeId': -1,
    //                       'refereeText': 'R',
    //                       'title':'Pitch is not available',
    //                       'color': 'grey',
    //                       'matchId':-1,
    //                       'matchAgeGroupId':''
    //                   }

    //                   if(availability.stage_start_time != '08:00'){
    //                       let mData1 = {
    //                           'id': 'start_'+counter,
    //                           'resourceId': pitch.id,
    //                           'start':moment.utc(availability.stage_start_date+' '+'08:00:00','DD/MM/YYYY HH:mm:ss'),
    //                           'end': moment.utc(availability.stage_start_date+' '+availability.stage_start_time,'DD/MM/YYYY hh:mm:ss'),
    //                           'refereeId': -1,
    //                           'refereeText': 'R',
    //                           'title': 'Pitch is not available',
    //                           'color': 'grey',
    //                           'matchId':-1,
    //                       'matchAgeGroupId':''
    //                       }
    //                       sMatches.push(mData1)
    //                   }
    //                   if(availability.stage_end_time != '19:00'){
    //                       let mData2 = {
    //                           'id': 'end_'+counter,
    //                           'resourceId': pitch.id,
    //                           'start':moment.utc(availability.stage_start_date+' '+availability.stage_end_time,'DD/MM/YYYY hh:mm:ss'),
    //                           'end': moment.utc(availability.stage_start_date+' '+'19:00:00','DD/MM/YYYY HH:mm:ss'),
    //                           'refereeId': -1,
    //                           'refereeText': 'R',
    //                           'title':'Pitch is not available',
    //                           'color': 'grey',
    //                           'matchId': -1,
    //                       'matchAgeGroupId':''
    //                       }
    //                   sMatches.push(mData2)
    //                   }

    //                   sMatches.push(mData)
    //                       counter = counter+1;
    //                   });
                      
    //               scheduledMatchesByStage = sMatches
    //               });
    //               console.log(scheduledMatchesByStage)
    //          commit(types.SET_SCHEDULED_MATCHES, scheduledMatchesByStage)
    //         // this.scheduledMatches =sMatches

    //         // this.getUnavailablePitch()
    //         // this.stageWithoutPitch()
    //         // setTimeout(function(){
    //         //     vm.initScheduler();
    //         // },1500)
    //     }
    // )           
  }
}

// mutations
const mutations = {
  [types.SET_TOURNAMENT_STATUS] (state, tournamentField) {
	state.tournamentStatus = tournamentField.tournamentStatus
  },
  [types.CURRENT_TOURNAMENT] (state, currentTournament) {
 	//alert(JSON.stringify(currentTournamentName))
	state.tournamentName = currentTournament.name
	state.tournamentStartDate = currentTournament.tournamentStartDate!='' ? currentTournament.tournamentStartDate: ''
	state.tournamentEndDate = currentTournament.tournamentEndDate != '' ? currentTournament.tournamentEndDate: ''
	state.tournamentDays = currentTournament.tournamentDays ? parseInt(currentTournament.tournamentDays)  : 1
	state.tournamentId = currentTournament.id
	state.tournamentStatus = currentTournament.tournamentStatus
	state.tournamentLogo = currentTournament.tournamentLogo

  // Optional Fields
  state.facebook = currentTournament.facebook
  state.website = currentTournament.website
  state.twitter = currentTournament.twitter
  },
  [types.SAVE_TOURNAMENT] (state, tournamentData) {

	state.tournamentName = tournamentData.name
	state.tournamentId = tournamentData.id
	state.tournamentStartDate = tournamentData.tournamentStartDate
	state.tournamentEndDate = tournamentData.tournamentEndDate
	state.tournamentStatus = tournamentData.tournamentStatus
	state.tournamentLogo = tournamentData.tournamentLogo
	state.tournamentDays = parseInt(tournamentData.tournamentDays )+ 1
  },
  [types.SAVE_COMPETATION_FORMAT] (state, competationFormatData) {
	// alert('hello in mutation')
	// state.templateData = competationFormatData.tournamentTemplate
	// state.tournamentId = tournamentData.id
	// state.currentPage = 'Competation Formats'
  },
  [types.SET_TEMPLATE] (state, templateData) {
	state.currentTemplate = templateData.json;
	state.currentTotalTime = templateData.TotalTime;
  },
  [types.SET_TOURNAMENT_TOTAL_TIME] (state, totalTime) {
	state.currentTotalTime = totalTime;
  },
 [types.SET_VENUES] (state, venueData) {
	state.venues = venueData;
  },
   [types.TOTAL_MATCHES] (state, totalMatch) {
	state.totalMatch = totalMatch;
  },
  [types.TOTAL_REFEREES] (state, totalReferees) {
	state.totalReferee = totalReferees;
  },
  
  [types.SET_TOURNAMENT_FILTER] (state, filterData) {
  	
	state.tournamentFiler.filterKey = filterData.filterKey;
	state.tournamentFiler.filterValue = filterData.filterValue;

  },
  [types.SET_REFEREES] (state, refereeData) {
  	state.referees = refereeData;
  },
  [types.COMPETITION_LIST] (state, competitionList) {
    state.competationList = competitionList;
  },
  [types.TOURNAMENT_STAGES] (state, tournamentStages) {
    state.tournamentStages = '';
    state.tournamentStages = tournamentStages;
  },
  
  [types.SET_MATCHES] (state, matches) {
    state.matches = '';
    state.matches = matches;
  },
  [types.SET_SCHEDULED_MATCHES] (state, scheduledMatches) {
    state.scheduledMatches = [];
    state.scheduledMatches = scheduledMatches;
  },
  [types.SET_COMPETITION_WITH_GAMES] (state) {
    // console.log(state.competationList,'state.competationList')
      let competitionGroup = state.competationList
      let allMatches = state.matches
      let matchCount = 0
      let matchCountDisplay = 0
      if(state.competationList.length > 0 && state.matches.length > 0){

        _.forEach(state.competationList, function(competition) {
        let cname = competition.group_name
        let comp = []
        let that = this
        matchCount = 0
        // matchCount = 0
          _.find(allMatches, function (match) {
            
            let round = ''
            let matchTime = 0
            if(match.group_name == competition.group_name){
              if(match.round == 'Round Robin'){
                round = 'RR-'
                matchTime = parseInt(competition.game_duration_RR) + parseInt(competition.halftime_break_RR) + parseInt(competition.match_interval_RR)
              }else if(match.round == 'Elimination'){
                round = 'EL-'
                matchTime = parseInt(competition.game_duration_FM) + parseInt(competition.halftime_break_FM) + parseInt(competition.match_interval_FM)

              }else if(match.round == 'Final'){
                round = 'FN-'
                matchTime = parseInt(competition.game_duration_FM) + parseInt(competition.halftime_break_FM) + parseInt(competition.match_interval_FM)
              }

              let fullgame1 = match.full_game;

              if(match.Away_id != 0 && match.Home_id != 0) {
                fullgame1 = ''
              }
               let mtchNumber = match.match_number
               let mtchNumber1 = mtchNumber.split(".")

              let mtchNum = mtchNumber1[0]+'.'+mtchNumber1[1]+"."
              if(match.Away_id != 0 && match.Home_id != 0)
              {
                 fullgame1 = ''
                 mtchNum = mtchNum+match.HomeTeam+'-'+match.AwayTeam
              } else {
                mtchNum = mtchNum+mtchNumber1[2]
              }

              var person = {'fullGame':fullgame1,'matchName':mtchNum,'matchTime':matchTime,'matchId': match.fid,'isScheduled': match.is_scheduled,'ageGroupId':match.age_group_id};
              comp.push(person)

              if(match.is_scheduled!=1){
                matchCount = matchCount + 1
                matchCountDisplay = matchCountDisplay + 1
              }
            }
            competition.matchCount = matchCount
          })
          competition.matchList = comp


        })
        // console.log(competationList,'')
        // console.log(state.competationList)
        state.matchCompetition = state.competationList
        state.totalMatch = matchCountDisplay
        // state.totalMatch = totalMatch;
        // this.$store.dispatch('SetTotalMatch', this.totalMatch)
         state.competitionWithGames = state.competationList
      }else{
        state.totalMatch = matchCountDisplay
        // this.$store.dispatch('SetTotalMatch', this.totalMatch)
        state.competitionWithGames = state.competationList
      }
    
    
  },

}

export default {
  state,
  getters,
  actions,
  mutations
}
