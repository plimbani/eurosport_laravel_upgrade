//
//  ForgotPasswordVC.m
//  ESR
//
//  Created by Aecor Digital on 22/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "ForgotPasswordVC.h"
#import "Globals.h"
#import "Utils.h"
#import "SVProgressHUD.h"
#import "UIColor+fromHex.h"
#import "Reachability.h"
@interface ForgotPasswordVC ()

@end

@implementation ForgotPasswordVC
-(UIView *)PaddingView{
    return [[UIView alloc] initWithFrame:CGRectMake(0, 0, 5, 20)];
}
- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view.
    self.scroll1EmailTxt.leftView = [self PaddingView];
    self.scroll1EmailTxt.leftViewMode = UITextFieldViewModeAlways;
    self.scroll2PasswordTxt.leftView = [self PaddingView];
    self.scroll2PasswordTxt.leftViewMode = UITextFieldViewModeAlways;
    self.scroll2OPTTxt.leftView = [self PaddingView];
    self.scroll2OPTTxt.leftViewMode = UITextFieldViewModeAlways;
    self.scroll2EmailTxt.leftView = [self PaddingView];
    self.scroll2EmailTxt.leftViewMode = UITextFieldViewModeAlways;

    UIColor *color = [UIColor grayColor];
    self.scroll1EmailTxt.attributedPlaceholder = [[NSAttributedString alloc] initWithString:@"Email address" attributes:@{NSForegroundColorAttributeName: color}];
    self.scroll2PasswordTxt.attributedPlaceholder = [[NSAttributedString alloc] initWithString:@"Password" attributes:@{NSForegroundColorAttributeName: color}];
    self.scroll2OPTTxt.leftView = [self PaddingView];
    self.scroll2OPTTxt.attributedPlaceholder = [[NSAttributedString alloc] initWithString:@"OTP" attributes:@{NSForegroundColorAttributeName: color}];
    self.scroll2EmailTxt.attributedPlaceholder = [[NSAttributedString alloc] initWithString:@"Email address" attributes:@{NSForegroundColorAttributeName: color}];
    
//    self.scroll1.hidden = TRUE;
//    self.scroll2.hidden = FALSE;
}
-(void)hideKeyboard
{
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
//    if (self.firstNameTxtField.text.length > 0 && self.surnameTxtField.text.length >0 && self.emailTxtField.text.length >0 && self.passwordTxtField.text.length >0 && self.confirmTxtField.text.length >0 && self.TournamentTxtField.text.length >0) {
//        self.signUpBtn.enabled = TRUE;
//        self.signUpBtn.backgroundColor =[UIColor colorwithHexString:@"ED9E2D" alpha:1.0];
//    }else{
//        self.signUpBtn.enabled = FALSE;
//        self.signUpBtn.backgroundColor =[UIColor colorwithHexString:@"CCCCCC" alpha:1.0];
//    }
    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(keyboardWillHide:)
                                                 name:UIKeyboardWillHideNotification
                                               object:nil];
    tap = [[UITapGestureRecognizer alloc] initWithTarget:self
                                                  action:@selector(dismissKeyboard)];
    
    [self.scrollSubView addGestureRecognizer:tap];
    [[NSNotificationCenter defaultCenter] addObserver: self selector: @selector(reachabilityChanged:) name: kReachabilityChangedNotification object: nil];
    Reachability* reachability = [Reachability reachabilityForInternetConnection];
    [reachability startNotifier];

    
}
-(void)dismissKeyboard {
    [self.scroll1EmailTxt resignFirstResponder];
    [self.scroll1 endEditing: YES];
    [self.scrollSubView endEditing:YES];
    [self scrollToY:0];
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
- (BOOL)textField:(UITextField *)textField shouldChangeCharactersInRange:(NSRange)range replacementString:(NSString *)string
{
    if (self.scroll1EmailTxt.text.length > 0 ) {
        if ([self validateEmailWithString:self.scroll1EmailTxt.text]) {
            self.scroll1GetOTPBtn.enabled = TRUE;
            self.scroll1GetOTPBtn.backgroundColor =[UIColor colorwithHexString:@"ED9E2D" alpha:1.0];
        }
    }else{
        self.scroll1GetOTPBtn.enabled = FALSE;
        self.scroll1GetOTPBtn.backgroundColor =[UIColor colorwithHexString:@"CCCCCC" alpha:1.0];
    }
    return YES;
}
- (BOOL)validateEmailWithString:(NSString*)email
{
    NSString *emailRegex = @"[A-Z0-9a-z._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,4}";
    NSPredicate *emailTest = [NSPredicate predicateWithFormat:@"SELF MATCHES %@", emailRegex];
    return [emailTest evaluateWithObject:email];
}
//- (BOOL)textField:(UITextField *)textField shouldChangeCharactersInRange:(NSRange)range replacementString:(NSString *)string
//{
//    
//    if (self.firstNameTxtField.text.length > 0 && self.surnameTxtField.text.length >0 && self.emailTxtField.text.length >0 && self.passwordTxtField.text.length >0 && self.confirmTxtField.text.length >0 && self.TournamentTxtField.text.length >0) {
//        self.signUpBtn.enabled = TRUE;
//        self.signUpBtn.backgroundColor =[UIColor colorwithHexString:@"ED9E2D" alpha:1.0];
//    }else{
//        self.signUpBtn.enabled = FALSE;
//        self.signUpBtn.backgroundColor =[UIColor colorwithHexString:@"CCCCCC" alpha:1.0];
//    }
//    
//    
//    return YES;
//}

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
    if (textField == self.scroll1EmailTxt) {
        [self.view endEditing:YES];
    }
    else if (textField == self.scroll2EmailTxt) {
        [self.scroll2OPTTxt becomeFirstResponder];
    }
    else if (textField == self.scroll2OPTTxt) {
        [self.scroll2PasswordTxt becomeFirstResponder];
    }
    else if (textField == self.scroll2PasswordTxt) {
       [self.view endEditing:YES];
    }
}
- (void)scrollViewWillBeginDragging:(UIScrollView *)scrollView
{
    [self.scroll1EmailTxt resignFirstResponder];
    [self.scroll2EmailTxt resignFirstResponder];
    [self.scroll2OPTTxt resignFirstResponder];
    [self.scroll2PasswordTxt resignFirstResponder];
    [self.scroll1 endEditing: YES];
    [self.scroll2 endEditing: YES];
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
    float y = theFrame.origin.y -30;
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

- (IBAction)scroll1GetOTPBtnClick:(id)sender {
    if (self.scroll1EmailTxt.text.length > 0 ) {
        if([Utils isNetworkAvailable] ==YES){
            [SVProgressHUD show];
            NSDictionary *params = @{@"email": self.scroll1EmailTxt.text};
            //NSDictionary *params = @{@"email": @"spatel@aecrodigital.com",@"password":@"sanjay1!" ,@"tournament_id":@"1",@"first_name":@"sanjay",@"sur_name":@"patel" };
            //Configure your session with common header fields like authorization etc
            NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
            sessionConfiguration.HTTPAdditionalHeaders = @{@"IsMobileUser": @"true"};
            NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
            NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,Forgotpassword ];
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
                                                  [SVProgressHUD dismiss];
                                                  if (error) {
                                                      NSLog(@"data%@",data);
                                                      NSLog(@"response%@",error);
                                                      //[SVProgressHUD dismiss];
                                                  } else{
                                                      NSError *parseError = nil;
                                                      NSDictionary *responseDictionary = [NSJSONSerialization JSONObjectWithData:data options:0 error:&parseError];
                                                      NSLog(@"%@",responseDictionary);
//                                                      self.scroll1.hidden = TRUE;
//                                                      self.scroll2.hidden = FALSE;
                                                      [self scrollToY:0];
                                                      [self.scroll1EmailTxt resignFirstResponder];
                                                      NSString *message = [responseDictionary valueForKey:@"message"];
                                                      if ([message isEqualToString:@"Success"]) {
                                                          dispatch_async(dispatch_get_main_queue(), ^{
                                                              self.alertViewTitle.text = @"Confirmation";
                                                              self.alertViewSubLbl.text = NSLocalizedString(@"We have sent a password reset link to your email address.", @"");
                                                              self.alertView.hidden = FALSE;
                                                          });
                                                      }else{
                                                          dispatch_async(dispatch_get_main_queue(), ^{
                                                              self.alertViewTitle.text = @"Error";
                                                              self.alertViewSubLbl.text = message;
                                                              self.alertView.hidden = FALSE;
                                                          });
                                                      }
                                                      
                                                      
//                                                      UIAlertController *alertController = [UIAlertController alertControllerWithTitle:@"ESR" message:message preferredStyle:UIAlertControllerStyleAlert];
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

- (IBAction)scroll2ChangePasswordBtnClick:(id)sender {
    if (self.scroll2EmailTxt.text.length > 0 && self.scroll2PasswordTxt.text.length>0 && self.scroll2OPTTxt.text.length >0 ) {
        if([Utils isNetworkAvailable] ==YES){
            NSDictionary *params = @{@"email": self.scroll1EmailTxt.text,@"password":self.scroll2PasswordTxt.text,@"otp":self.scroll2OPTTxt.text};
            //NSDictionary *params = @{@"email": @"spatel@aecrodigital.com",@"password":@"sanjay1!" ,@"tournament_id":@"1",@"first_name":@"sanjay",@"sur_name":@"patel" };
            //Configure your session with common header fields like authorization etc
            NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
            sessionConfiguration.HTTPAdditionalHeaders = @{@"IsMobileUser": @"true"};
            NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
            NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,Resetpassword ];
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
                                                      //[SVProgressHUD dismiss];
                                                  } else{
                                                      NSError *parseError = nil;
                                                      NSDictionary *responseDictionary = [NSJSONSerialization JSONObjectWithData:data options:0 error:&parseError];
                                                      NSLog(@"%@",responseDictionary);
                                                      NSString *message = [responseDictionary valueForKey:@"message"];
                                                      if ([message isEqualToString:@"Success"]) {
                                                          UIAlertController * alert=[UIAlertController alertControllerWithTitle: NSLocalizedString(@"Success", @"")
                                                                                                                        message:@"Password Reset sucessfully"
                                                                                                                 preferredStyle:UIAlertControllerStyleAlert];
                                                          
                                                          UIAlertAction* yesButton = [UIAlertAction actionWithTitle:@"Ok"
                                                                                                              style:UIAlertActionStyleDefault
                                                                                                            handler:^(UIAlertAction * action)
                                                          {
                                                              [self.navigationController popToRootViewControllerAnimated:TRUE];
                                                          }];
                                                          
                                                          [alert addAction:yesButton];
                                                          [self presentViewController:alert animated:YES completion:nil];
                                                      }else{
                                                          UIAlertController *alertController = [UIAlertController alertControllerWithTitle:NSLocalizedString(@"Error", @"") message:message preferredStyle:UIAlertControllerStyleAlert];
                                                          
                                                          UIAlertAction* ok = [UIAlertAction actionWithTitle:@"OK" style:UIAlertActionStyleDefault handler:nil];
                                                          [alertController addAction:ok];
                                                          
                                                          [self presentViewController:alertController animated:YES completion:nil];
                                                      }
                                                  }
                                              }];
            [dataTask resume];
        }else{
            
        }
    }
}

- (IBAction)scroll1BackBtnClick:(id)sender {
    [self.navigationController popViewControllerAnimated:TRUE];
}

- (IBAction)scroll2BackBtnClick:(id)sender {
    self.scroll2.hidden = TRUE;
    self.scroll1.hidden = FALSE;
}
- (IBAction)alertViewOkBtnClick:(id)sender {
    [self.navigationController popViewControllerAnimated:TRUE];
}
@end
