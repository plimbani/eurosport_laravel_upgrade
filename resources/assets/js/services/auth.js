import Ls from './ls'

export default {

    login(loginData){
        return axios.post('/api/auth/login', loginData).then(response =>  {
            Ls.set('auth.token',response.data.token)
            // We set Email Over here 
            Ls.set('email',loginData.email)
           
        }).catch(error => {
            if (error.response.status == 401) {
                toastr['error']('Invalid Credentials', 'Error');
                
                Ls.remove('auth.token')
                Ls.remove('email')
            } else {
                // Something happened in setting up the request that triggered an Error
                console.log('Error', error.message);
            }
        });

    },

    logout(){
        return axios.get('/api/auth/logout').then(response =>  {
            Ls.remove('auth.token')
            toastr['success']('Logged out!', 'Success');
        }).catch(error => {
            console.log('Error', error.message);
        });
    },

    check(){
        return axios.get('/api/auth/check').then(response =>  {
            return !!response.data.authenticated;
        }).catch(error => {
            console.log('Error', error.message);
        });
    },

}