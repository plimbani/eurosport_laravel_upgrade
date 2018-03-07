//
//  LoginVC.m
//  ESR
//
//  Created by Aecor Digital on 15/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "LoginVC.h"
#import "LoginVC.h"
#import "Globals.h"
#import "Utils.h"
#import "AppDelegate.h"
#import "HomeTabBar.h"
#import "UIColor+fromHex.h"
#import "SVProgressHUD.h"
#import "Reachability.h"

@interface LoginVC ()

@end

@implementation LoginVC
-(UIView *)PaddingView{
    return [[UIView alloc] initWithFrame:CGRectMake(0, 0, 15, 20)];
}
-(void)GetDefaultTournament{
    
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    NSDictionary *userData = [defaults objectForKey:@"userData"];
    if([Utils isNetworkAvailable] ==YES){
        [SVProgressHUD show];
        NSDictionary *params = @{@"user_id":[userData valueForKey:@"user_id"] };
        NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
        NSString *token = [defaults objectForKey:@"token"];
        NSString *concateToken = [NSString stringWithFormat:@"%@%@",@"Bearer ",token];
        NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
        NSDictionary *header =@{@"IsMobileUser": @"true",@"Authorization":concateToken};
        sessionConfiguration.HTTPAdditionalHeaders = header;
        NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
        NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,GetTournamentDefault];
        NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:[NSURL URLWithString:url]
                                                               cachePolicy:NSURLRequestUseProtocolCachePolicy timeoutInterval:60.0];
        NSData *requestData = [NSJSONSerialization dataWithJSONObject:params options:0 error:nil]; //TODO handle error
        [request setHTTPMethod:@"POST"];
        [request setValue:@"application/json" forHTTPHeaderField:@"Accept"];
        [request setValue:@"application/json; charset=utf-8" forHTTPHeaderField:@"Content-Type"];
        [request setValue:[NSString stringWithFormat:@"%lu", (unsigned long)[requestData length]] forHTTPHeaderField:@"Content-Length"];
        [request setHTTPBody: requestData];
        NSURLSessionDataTask *dataTask = [session dataTaskWithRequest:request
                                                    completionHandler:^(NSData *data, NSURLResponse *response, NSError *error)
                                          {
                                              if (error) {
                                                  NSLog(@"data%@",data);
                                                  NSLog(@"response%@",error);
                                                  [SVProgressHUD dismiss];
                                              } else{
                                                  [SVProgressHUD dismiss];
                                                  
                                                  NSError *parseError = nil;
                                                  NSDictionary *responseDictionary = [NSJSONSerialization JSONObjectWithData:data options:0 error:&parseError];
                                                  AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
                                                  app.defaultTournamentDir =[responseDictionary[@"data"] mutableCopy];
                                                  dispatch_async(dispatch_get_main_queue(), ^{
                                                      NSLog(@"%@",app.firebaseToken);
                                                      if(app.firebaseToken != NULL){
                                                          [self sendToken:app.firebaseToken];
                                                      }
                                                      
                                                      UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
                                                      //                                                      HomeTabBar *myVC = (HomeTabBar *)[storyboard instantiateViewControllerWithIdentifier:@"HomeTabBar"];
                                                      //                                                      [self.navigationController pushViewController:myVC animated:YES];
                                                      AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
                                                      app.window = [[UIWindow alloc] initWithFrame:UIScreen.mainScreen.bounds];
                                                      //                                                      UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
                                                      HomeTabBar *myVC = (HomeTabBar *)[storyboard instantiateViewControllerWithIdentifier:@"HomeTabBar"];
                                                      myVC.selectedIndex = 1;
                                                      UINavigationController *navigationObject = [[UINavigationController alloc] initWithRootViewController:myVC];
                                                      app.window.rootViewController = navigationObject;
                                                      navigationObject.navigationBar.hidden = TRUE;
                                                      [app.window makeKeyAndVisible];
                                                      [self.navigationController popToRootViewControllerAnimated:TRUE];
                                                  });
                                                  //NSLog(@"%@",defaultTournamentDir);
                                                  
                                                  //                                                  selectedTournament = [[responseDictionary valueForKey:@"tournament_id"] integerValue];
                                                  //                                                  tournamentName = [responseDictionary valueForKey:@"name"];
                                                  
                                              }
                                          }];
        [dataTask resume];
    }else{
        
    }
}
- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view.
    self.emailTxtField.delegate = self;
    self.passwordTxtField.delegate = self;
    self.emailTxtField.leftView = [self PaddingView];
    self.emailTxtField.leftViewMode = UITextFieldViewModeAlways;
    self.passwordTxtField.leftView = [self PaddingView];
    self.passwordTxtField.leftViewMode = UITextFieldViewModeAlways;
    UIColor *color = [UIColor grayColor];
    self.emailTxtField.attributedPlaceholder = [[NSAttributedString alloc] initWithString:@"Email address" attributes:@{NSForegroundColorAttributeName: color}];
    self.passwordTxtField.attributedPlaceholder = [[NSAttributedString alloc] initWithString:@"Password" attributes:@{NSForegroundColorAttributeName: color}];
    if (self.emailTxtField.text.length > 0 && self.passwordTxtField.text.length >0) {
        self.loginBtn.enabled = TRUE;
        self.loginBtn.backgroundColor =[UIColor colorwithHexString:@"ED9E2D" alpha:1.0];
    }else{
        self.loginBtn.enabled = FALSE;
        self.loginBtn.backgroundColor =[UIColor colorwithHexString:@"CCCCCC" alpha:1.0];
    }
    UITapGestureRecognizer *tap = [[UITapGestureRecognizer alloc] initWithTarget:self
                                                                          action:@selector(dismissKeyboard)];
    [self.scrollSubView addGestureRecognizer:tap];
}

-(void)dismissKeyboard {
    
    [self.emailTxtField resignFirstResponder];
    [self.passwordTxtField resignFirstResponder];
   
    [self.scroll endEditing: YES];
    [self.scrollSubView endEditing:YES];
    [self scrollToY:0];
}
- (void)reachabilityChanged:(NSNotification*)notification
{
    Reachability* reachability = notification.object;
    if(reachability.currentReachabilityStatus == NotReachable)
        self.offlineView.hidden = false;
    else
        self.offlineView.hidden = TRUE;
}
- (void)viewWillAppear:(BOOL)animated
{
    [super viewWillAppear:animated];
    [self scrollToY:0];
    if([Utils isNetworkAvailable] ==YES){
        self.offlineView.hidden = TRUE;
    }else{
        self.offlineView.hidden = false;
    }
    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(keyboardWillHide:)
                                                 name:UIKeyboardWillHideNotification
                                               object:nil];
    [[NSNotificationCenter defaultCenter] addObserver: self selector: @selector(reachabilityChanged:) name: kReachabilityChangedNotification object: nil];
    Reachability* reachability = [Reachability reachabilityForInternetConnection];
    [reachability startNotifier];
    
}
- (void)viewDidDisappear:(BOOL)animated
{
    [super viewDidDisappear:animated];
    if (![[self.navigationController viewControllers] containsObject: self]) //any other hierarchy compare if it contains self or not
    {
        // the view has been removed from the navigation stack or hierarchy, back is probably the cause
        // this will be slow with a large stack however.
        
        [[NSNotificationCenter defaultCenter] removeObserver:self];
    }
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/
- (BOOL)textField:(UITextField *)textField shouldChangeCharactersInRange:(NSRange)range replacementString:(NSString *)string
{
    
    if (self.emailTxtField.text.length > 0 && self.passwordTxtField.text.length >0) {
        if ([self validateEmailWithString:self.emailTxtField.text]) {
            self.loginBtn.enabled = TRUE;
            self.loginBtn.backgroundColor =[UIColor colorwithHexString:@"ED9E2D" alpha:1.0];
        }
        
    }else{
        self.loginBtn.enabled = FALSE;
        self.loginBtn.backgroundColor =[UIColor colorwithHexString:@"CCCCCC" alpha:1.0];
    }
    
    
    return YES;
}
- (BOOL)validateEmailWithString:(NSString*)email
{
    NSString *emailRegex = @"[A-Z0-9a-z._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,4}";
    NSPredicate *emailTest = [NSPredicate predicateWithFormat:@"SELF MATCHES %@", emailRegex];
    return [emailTest evaluateWithObject:email];
}
- (BOOL)textFieldShouldReturn:(UITextField*)textField
{
    [textField resignFirstResponder];
    return NO;
}
- (BOOL)textFieldShouldBeginEditing:(UITextField*)textField
{
    [self scrollToView:textField];
    return YES;
}

- (void)textFieldDidEndEditing:(UITextField*)textField
{
    if (textField == self.emailTxtField) {
        [self.passwordTxtField becomeFirstResponder];
    }
    else if (textField == self.passwordTxtField) {
        [self.passwordTxtField resignFirstResponder];
        [self.view endEditing:YES];
    }
}
- (void)scrollViewWillBeginDragging:(UIScrollView *)scrollView
{
    [self.passwordTxtField resignFirstResponder];
    [self.emailTxtField resignFirstResponder];
    [self.scroll endEditing: YES];
}
- (void)scrollToY:(float)y
{
    [UIView beginAnimations:@"registerScroll" context:NULL];
    [UIView setAnimationCurve:UIViewAnimationCurveEaseInOut];
    [UIView setAnimationDuration:0.4];
    self.scrollSubView.transform = CGAffineTransformMakeTranslation(0, y);
    [UIView commitAnimations];
}

- (void)scrollToView:(UIView*)view
{
    CGRect theFrame = view.frame;
    float y = theFrame.origin.y -90;
    //float y = theFrame.origin.y + theFrame.size.height -90;
    y -= (y / 1.7);
    [self scrollToY:-y];
}

- (void)scrollElement:(UIView*)view toPoint:(float)y
{
    CGRect theFrame = view.frame;
    float orig_y = theFrame.origin.y;
    float diff = y - orig_y;
    if (diff < 0) {
        [self scrollToY:diff];
    }
    else {
        [self scrollToY:0];
    }
}

- (void)keyboardWillHide:(NSNotification*)notification
{
    [self scrollToY:0];
}
-(void)sendToken:(NSString *)registration_id{
    NSDictionary *params = @{@"email": self.emailTxtField.text, @"fcm_id": registration_id};
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    NSString *token = [defaults objectForKey:@"token"];
    NSString *concateToken = [NSString stringWithFormat:@"%@%@",@"Bearer ",token];
    NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
    NSDictionary *header =@{@"IsMobileUser": @"true",@"Authorization":concateToken};
    sessionConfiguration.HTTPAdditionalHeaders = header;
    NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
    NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,PushNotification];
    NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:[NSURL URLWithString:url]
                                                           cachePolicy:NSURLRequestUseProtocolCachePolicy timeoutInterval:60.0];
    NSData *requestData = [NSJSONSerialization dataWithJSONObject:params options:0 error:nil]; //TODO handle error
    [request setHTTPMethod:@"POST"];
    [request setValue:@"application/json" forHTTPHeaderField:@"Accept"];
    [request setValue:@"application/json; charset=utf-8" forHTTPHeaderField:@"Content-Type"];
    [request setValue:[NSString stringWithFormat:@"%lu", (unsigned long)[requestData length]] forHTTPHeaderField:@"Content-Length"];
    [request setHTTPBody: requestData];
    NSURLSessionDataTask *dataTask = [session dataTaskWithRequest:request
                                                completionHandler:^(NSData *data, NSURLResponse *response, NSError *error)
                                      {
                                          if (error) {
                                              NSLog(@"data%@",data);
                                              NSLog(@"response%@",error);
                                              [SVProgressHUD dismiss];
                                          } else{
                                              [SVProgressHUD dismiss];
                                              NSError *parseError = nil;
                                              NSDictionary *responseDictionary = [NSJSONSerialization JSONObjectWithData:data options:0 error:&parseError];
                                              NSLog(@"%@",responseDictionary);
                                              
                                          }
                                      }];
    [dataTask resume];
    

}
-(void)checkApi:(NSString *)token{
    NSString *concateToken = [NSString stringWithFormat:@"%@%@",@"Bearer ",token];
    if([Utils isNetworkAvailable] ==YES){
//        NSMutableURLRequest *request = [[NSMutableURLRequest alloc]init];
        NSString *post = @"";
        NSData *postData = [post dataUsingEncoding:NSUTF8StringEncoding allowLossyConversion:YES];
//        NSString *postLength = [NSString stringWithFormat:@"%lu",(unsigned long)[postData length]];
        NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,CheckApi];
        //[request setURL:[NSURL URLWithString:url]];
        NSDictionary *header =@{@"IsMobileUser": @"true",@"Authorization":concateToken};
        NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
        sessionConfiguration.HTTPAdditionalHeaders =header;
        NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
        
//        NSLog(@"postLength =%@",postLength);
//        
//        [request setHTTPBody:postData];
//        [request setHTTPMethod:@"GET"];
//        [request addValue:post forHTTPHeaderField:@"GET"];
//        [request setValue:@"application/json" forHTTPHeaderField:@"Content-Type"];
        
        NSDictionary *params =@"";
        NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:[NSURL URLWithString:url]
                                                               cachePolicy:NSURLRequestUseProtocolCachePolicy timeoutInterval:60.0];
        //NSData *requestData = [NSJSONSerialization dataWithJSONObject:params options:0 error:nil]; //TODO handle error
        [request setHTTPMethod:@"POST"];
        [request setValue:@"application/json" forHTTPHeaderField:@"Accept"];
        [request setValue:@"application/json; charset=utf-8" forHTTPHeaderField:@"Content-Type"];
        [request setValue:[NSString stringWithFormat:@"%lu", (unsigned long)[postData length]] forHTTPHeaderField:@"Content-Length"];
        [request setHTTPBody: postData];
        
        
        
        NSURLSessionDataTask *dataTask = [session dataTaskWithRequest:request
                                                    completionHandler:^(NSData *data, NSURLResponse *response, NSError *error)
                                          {
                                              [SVProgressHUD dismiss];
                                              if (error) {
                                                  [SVProgressHUD dismiss];
                                                  NSLog(@"data%@",data);
                                                  NSLog(@"response%@",error);
                                                  //[SVProgressHUD dismiss];
                                              } else{
                                                  [SVProgressHUD dismiss];
                                                  NSError *parseError = nil;
                                                  NSDictionary *responseDictionary = [NSJSONSerialization JSONObjectWithData:data options:0 error:&parseError];
                                                  int authenticated = [[responseDictionary valueForKey:@"authenticated"] intValue];
                                                  
                                                  if (authenticated == 1) {
                                                      dispatch_async(dispatch_get_main_queue(), ^{
                                                          NSDictionary *user = [responseDictionary valueForKey:@"userData"];
                                                          NSLog(@"%@",user);
                                                          [[NSUserDefaults standardUserDefaults] setObject:token forKey:@"token"];
                                                          NSDictionary *userDataDir =@{@"email":[user valueForKey:@"email"],@"password":self.passwordTxtField.text,@"first_name":[user valueForKey:@"first_name"],@"locale":[user valueForKey:@"locale"],@"profile_image_url":[user valueForKey:@"profile_image_url"],@"sur_name":[user valueForKey:@"sur_name"],@"tournament_id":[user valueForKey:@"tournament_id"],@"user_id":[user valueForKey:@"user_id"]};
                                                          [[NSUserDefaults standardUserDefaults] setObject:userDataDir forKey:@"userData"];
                                                          [[NSUserDefaults standardUserDefaults] setObject:@"1" forKey:@"loginflag"];
                                                          [[NSUserDefaults standardUserDefaults] synchronize];
                                                          [self GetDefaultTournament];
                                                      });
                                                      
                                                      
                                                  }else{
                                                      [SVProgressHUD dismiss];
                                                  }
                                                  NSLog(@"%@",responseDictionary);
                                                
                                                  
                                              }
                                          }];
        [dataTask resume];
    }
}
- (IBAction)loginBtnClick:(id)sender {
    
   
    if (self.emailTxtField.text.length > 0 && self.passwordTxtField.text.length >0 ) {
        if([Utils isNetworkAvailable] ==YES){
            [SVProgressHUD show];
            NSDictionary *params = @{@"email": self.emailTxtField.text,@"password":self.passwordTxtField.text ,@"forgotpassword":@"0",@"remember":@""};
            //NSDictionary *params = @{@"email": @"spatel@aecrodigital.com",@"password":@"sanjay1!" ,@"tournament_id":@"1",@"first_name":@"sanjay",@"sur_name":@"patel" };
            //Configure your session with common header fields like authorization etc
            NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
            sessionConfiguration.HTTPAdditionalHeaders = @{@"IsMobileUser": @"true"};
            NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
            NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,Login ];
            //NSString *url =@"https://manager.gimbal.com/api/v2/places";
            NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:[NSURL URLWithString:url]
                                                                   cachePolicy:NSURLRequestUseProtocolCachePolicy timeoutInterval:60.0];
            NSData *requestData = [NSJSONSerialization dataWithJSONObject:params options:0 error:nil]; //TODO handle error
            [request setHTTPMethod:@"POST"];
            [request setValue:@"application/json" forHTTPHeaderField:@"Accept"];
            [request setValue:@"application/json; charset=utf-8" forHTTPHeaderField:@"Content-Type"];
            [request setValue:[NSString stringWithFormat:@"%lu", (unsigned long)[requestData length]] forHTTPHeaderField:@"Content-Length"];
            [request setHTTPBody: requestData];
            NSURLSessionDataTask *dataTask = [session dataTaskWithRequest:request
                                                        completionHandler:^(NSData *data, NSURLResponse *response, NSError *error)
                                              {
                                                  if (error) {
                                                      NSLog(@"data%@",data);
                                                      NSLog(@"response%@",error);
                                                      [SVProgressHUD dismiss];
                                                  } else{
                                                      NSError *parseError = nil;
                                                      NSDictionary *responseDictionary = [NSJSONSerialization JSONObjectWithData:data options:0 error:&parseError];
                                                      NSLog(@"%@",responseDictionary);
                                                      NSString *token =responseDictionary[@"token"];
                                                      NSString *error =responseDictionary[@"error"];
                                                      if(token != NULL){
                                                          [self checkApi:token];
                                                          
                                                      }else{
                                                          [SVProgressHUD dismiss];
                                                          
                                                          if (error != NULL) {
                                                              dispatch_async(dispatch_get_main_queue(), ^{
                                                                  self.alertView.hidden = FALSE;
                                                                  self.alertViewTitle.text = @"Error";
                                                                  self.alertViewSubtitle.text = error;
//                                                                  UIAlertController *alertController = [UIAlertController alertControllerWithTitle:@"Error" message:error preferredStyle:UIAlertControllerStyleAlert];
//                                                                  
//                                                                  UIAlertAction* ok = [UIAlertAction actionWithTitle:@"OK" style:UIAlertActionStyleDefault handler:nil];
//                                                                  [alertController addAction:ok];
//                                                                  
//                                                                  [self presentViewController:alertController animated:YES completion:nil];
                                                              });
                                                          }
                                                      }
                                                      
                                                      
                                                      
//                                                      NSString *message = [responseDictionary valueForKey:@"message"];
//                                                      UIAlertController *alertController = [UIAlertController alertControllerWithTitle:@"Error" message:message preferredStyle:UIAlertControllerStyleAlert];
//                                                      UIAlertAction* ok = [UIAlertAction actionWithTitle:@"OK" style:UIAlertActionStyleDefault handler:^(UIAlertAction * action) {
//                                                          
//                                                          [self.navigationController popViewControllerAnimated:TRUE];                                                          }];
//                                                      [alertController addAction:ok];
//                                                      [self presentViewController:alertController animated:YES completion:nil];
                                                  }
                                              }];
            [dataTask resume];
        }else{
            
        }
    }
}

- (IBAction)forgotPasswordBtnClick:(id)sender {
}

- (IBAction)backBtnClick:(id)sender {
    [self.navigationController popViewControllerAnimated:TRUE];
}

- (IBAction)alertViewOkBtnClick:(id)sender {
    self.alertView.hidden = TRUE;
}
@end
