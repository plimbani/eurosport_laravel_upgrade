import Ls from './ls'

export default {
    login(loginData){
        return axios.post('/api/auth/login', loginData).then(response =>  {
            Ls.set('auth.token',response.data.token)
            // We set Email Over here
            Ls.set('email',loginData.email)

        }).catch(error => {
            if (error.response.status == 401) {
                toastr['error']('Invalid credentials', 'Error');
                Ls.remove('auth.token')
                Ls.remove('email')
            }
            else {
                // Something happened in setting up the request that triggered an Error
            }
        });
    },

    register(registerData){
        console.log("register function call");
        // need to change in the API in url and also in function
         return axios.post('/api/v1/commercialisation/thankyou', registerData).then(response =>  {
             console.log("response in register::",response); 
             if (response.data.status_code == 422) {
                 toastr['error'](response.data.message, 'Error');
             }
         }).catch(error => {
             console.log("error in register::",error);
         });
    },
    logout(){
        return axios.get('/api/auth/logout').then(response =>  {
            Ls.remove('auth.token')
            Ls.remove('email')
            Ls.remove('vuex')
            Ls.remove('userData')
            // here we have to reload the page
            toastr['success']('Logged out!', 'Success');
            setTimeout(Plugin.reloadPage, 1000);
        }).catch(error => {
        });
        // Reload
    },
    check(data){
        return axios.post('/api/auth/check', data).then(response =>  {
            if(response.data.authenticated == false) {
              if(response.data.message != undefined) {
                toastr['error'](response.data.message, 'Error');
              }
            }
            return response.data;
        }).catch(error => {
        });
    },
}
