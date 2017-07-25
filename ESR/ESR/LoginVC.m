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

@interface LoginVC ()

@end

@implementation LoginVC
-(UIView *)PaddingView{
    return [[UIView alloc] initWithFrame:CGRectMake(0, 0, 5, 20)];
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
    self.emailTxtField.attributedPlaceholder = [[NSAttributedString alloc] initWithString:@"Enter email" attributes:@{NSForegroundColorAttributeName: color}];
    self.passwordTxtField.attributedPlaceholder = [[NSAttributedString alloc] initWithString:@"Enter password" attributes:@{NSForegroundColorAttributeName: color}];
    if (self.emailTxtField.text.length > 0 && self.passwordTxtField.text.length >0) {
        self.loginBtn.enabled = TRUE;
        self.loginBtn.backgroundColor =[UIColor colorwithHexString:@"ED9E2D" alpha:1.0];
    }else{
        self.loginBtn.enabled = FALSE;
        self.loginBtn.backgroundColor =[UIColor colorwithHexString:@"CCCCCC" alpha:1.0];
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
        self.loginBtn.enabled = TRUE;
        self.loginBtn.backgroundColor =[UIColor colorwithHexString:@"ED9E2D" alpha:1.0];
    }else{
        self.loginBtn.enabled = FALSE;
        self.loginBtn.backgroundColor =[UIColor colorwithHexString:@"CCCCCC" alpha:1.0];
    }
    
    
    return YES;
}
- (BOOL)textFieldShouldReturn:(UITextField*)textField
{
    [textField resignFirstResponder];
    return NO;
}
- (void)textFieldDidEndEditing:(UITextField*)textField
{
    if (textField == self.emailTxtField) {
        [self.passwordTxtField becomeFirstResponder];
    }
    else if (textField == self.passwordTxtField) {
        [self.passwordTxtField resignFirstResponder];
    }
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
        [request setHTTPMethod:@"GET"];
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
                                                      [[NSUserDefaults standardUserDefaults] setObject:token forKey:@"token"];
                                                      [[NSUserDefaults standardUserDefaults] setObject:[responseDictionary valueForKey:@"userData"] forKey:@"userData"];
                                                      [[NSUserDefaults standardUserDefaults] setObject:@"1" forKey:@"loginflag"];
                                                      [[NSUserDefaults standardUserDefaults] synchronize];
                                                      dispatch_async(dispatch_get_main_queue(), ^{
                                                          UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
                                                          //                                                      HomeTabBar *myVC = (HomeTabBar *)[storyboard instantiateViewControllerWithIdentifier:@"HomeTabBar"];
                                                          //                                                      [self.navigationController pushViewController:myVC animated:YES];
                                                          AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
                                                          app.window = [[UIWindow alloc] initWithFrame:UIScreen.mainScreen.bounds];
                                                          //                                                      UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
                                                          HomeTabBar *myVC = (HomeTabBar *)[storyboard instantiateViewControllerWithIdentifier:@"HomeTabBar"];
                                                          UINavigationController *navigationObject = [[UINavigationController alloc] initWithRootViewController:myVC];
                                                          app.window.rootViewController = navigationObject;
                                                          navigationObject.navigationBar.hidden = TRUE;
                                                          [app.window makeKeyAndVisible];
                                                          [self.navigationController popToRootViewControllerAnimated:TRUE];
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
