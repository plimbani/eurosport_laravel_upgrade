//
//  SignUPVC.m
//  ESR
//
//  Created by Aecor Digital on 15/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "SignUPVC.h"
#import <AFNetworking/AFNetworking.h>
#import "LoginVC.h"
#import "Globals.h"
#import "Utils.h"
#import "SignUpTournamentList.h"
#import "UIColor+fromHex.h"
#import "SVProgressHUD.h"

@interface SignUPVC ()

@end

@implementation SignUPVC
@synthesize selectedTournamentIndex;
-(UIView *)PaddingView{
    return [[UIView alloc] initWithFrame:CGRectMake(0, 0, 5, 20)];
}
- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view.
    //UIView *paddingView = [[UIView alloc] initWithFrame:CGRectMake(0, 0, 5, 20)];
    self.surnameTxtField.delegate = self;
    self.emailTxtField.delegate = self;
    self.confirmTxtField.delegate = self;
    self.passwordTxtField.delegate = self;
    self.firstNameTxtField.delegate = self;
    self.TournamentTxtField.delegate = self;
    self.surnameTxtField.leftView = [self PaddingView];
    self.surnameTxtField.leftViewMode = UITextFieldViewModeAlways;
    self.emailTxtField.leftView = [self PaddingView];
    self.emailTxtField.leftViewMode = UITextFieldViewModeAlways;
    self.passwordTxtField.leftView = [self PaddingView];
    self.passwordTxtField.leftViewMode = UITextFieldViewModeAlways;
    self.firstNameTxtField.leftView = [self PaddingView];
    self.firstNameTxtField.leftViewMode = UITextFieldViewModeAlways;
    self.confirmTxtField.leftView = [self PaddingView];
    self.confirmTxtField.leftViewMode = UITextFieldViewModeAlways;
    self.TournamentTxtField.leftView = [self PaddingView];
    self.TournamentTxtField.leftViewMode = UITextFieldViewModeAlways;
    UIColor *color = [UIColor grayColor];
    self.surnameTxtField.attributedPlaceholder = [[NSAttributedString alloc] initWithString:@"Surname" attributes:@{NSForegroundColorAttributeName: color}];
    self.emailTxtField.attributedPlaceholder = [[NSAttributedString alloc] initWithString:@"Email" attributes:@{NSForegroundColorAttributeName: color}];
    self.passwordTxtField.attributedPlaceholder = [[NSAttributedString alloc] initWithString:@"Password" attributes:@{NSForegroundColorAttributeName: color}];
    self.firstNameTxtField.attributedPlaceholder = [[NSAttributedString alloc] initWithString:@"First name" attributes:@{NSForegroundColorAttributeName: color}];
    self.confirmTxtField.attributedPlaceholder = [[NSAttributedString alloc] initWithString:@"Confirm password" attributes:@{NSForegroundColorAttributeName: color}];
    self.TournamentTxtField.attributedPlaceholder = [[NSAttributedString alloc] initWithString:@"Tournament" attributes:@{NSForegroundColorAttributeName: color}];
    
    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(tournamentSelect:)
                                                 name:@"tournamentSelect" object:nil];
    
}
- (void)tournamentSelect:(NSNotification *)message {
    self.TournamentTxtField.text = [message.object valueForKey:@"Name"];
    selectedTournamentIndex =[message.object valueForKey:@"index"];
    NSLog(@"%@",selectedTournamentIndex);
    
}
-(void)hideKeyboard
{
    [self scrollToY:0];
}
- (void)viewWillAppear:(BOOL)animated
{
    [super viewWillAppear:animated];
    [self scrollToY:0];
    if (self.firstNameTxtField.text.length > 0 && self.surnameTxtField.text.length >0 && self.emailTxtField.text.length >0 && self.passwordTxtField.text.length >0 && self.confirmTxtField.text.length >0 && self.TournamentTxtField.text.length >0) {
        self.signUpBtn.enabled = TRUE;
        self.signUpBtn.backgroundColor =[UIColor colorwithHexString:@"ED9E2D" alpha:1.0];
    }else{
        self.signUpBtn.enabled = FALSE;
        self.signUpBtn.backgroundColor =[UIColor colorwithHexString:@"CCCCCC" alpha:1.0];
    }
    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(keyboardWillHide:)
                                                 name:UIKeyboardWillHideNotification
                                               object:nil];
    
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
//-(void)viewWillDisappear:(BOOL)animated{
//    [super viewDidDisappear:animated];
//    [[NSNotificationCenter defaultCenter] removeObserver:self];
//}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}
- (BOOL)textField:(UITextField *)textField shouldChangeCharactersInRange:(NSRange)range replacementString:(NSString *)string
{
    
    if (self.firstNameTxtField.text.length > 0 && self.surnameTxtField.text.length >0 && self.emailTxtField.text.length >0 && self.passwordTxtField.text.length >0 && self.confirmTxtField.text.length >0 && self.TournamentTxtField.text.length >0) {
        self.signUpBtn.enabled = TRUE;
        self.signUpBtn.backgroundColor =[UIColor colorwithHexString:@"ED9E2D" alpha:1.0];
    }else{
        self.signUpBtn.enabled = FALSE;
        self.signUpBtn.backgroundColor =[UIColor colorwithHexString:@"CCCCCC" alpha:1.0];
    }
    
    
    return YES;
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
    if (textField == self.firstNameTxtField) {
        [self.surnameTxtField becomeFirstResponder];
    }
    else if (textField == self.surnameTxtField) {
        [self.emailTxtField becomeFirstResponder];
    }
    else if (textField == self.emailTxtField) {
        [self.passwordTxtField becomeFirstResponder];
    }
    else if (textField == self.passwordTxtField) {
        //[self.view endEditing:YES];
        [self.confirmTxtField becomeFirstResponder];
    }
    else if (textField == self.confirmTxtField) {
        [self.view endEditing:YES];
    }
}
- (void)scrollViewWillBeginDragging:(UIScrollView *)scrollView
{
    [self.firstNameTxtField resignFirstResponder];
    [self.passwordTxtField resignFirstResponder];
    [self.confirmTxtField resignFirstResponder];
    [self.surnameTxtField resignFirstResponder];
    [self.emailTxtField resignFirstResponder];
    [self.TournamentTxtField resignFirstResponder];
    [self.scroll endEditing: YES];
}
- (void)scrollToY:(float)y
{
    [UIView beginAnimations:@"registerScroll" context:NULL];
    [UIView setAnimationCurve:UIViewAnimationCurveEaseInOut];
    [UIView setAnimationDuration:0.4];
    self.view.transform = CGAffineTransformMakeTranslation(0, y);
    [UIView commitAnimations];
}

- (void)scrollToView:(UIView*)view
{
    CGRect theFrame = view.frame;
    float y = theFrame.origin.y + theFrame.size.height +0;
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
/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/

- (IBAction)signUpBtnClick:(id)sender {
    if (self.firstNameTxtField.text.length > 0 && self.surnameTxtField.text.length >0 && self.emailTxtField.text.length >0 && self.passwordTxtField.text.length >0 && self.confirmTxtField.text.length >0 && self.TournamentTxtField.text.length >0) {
        if ([_confirmTxtField.text isEqualToString:_passwordTxtField.text]) {
            if([Utils isNetworkAvailable] ==YES){
                [SVProgressHUD show];
                NSDictionary *params = @{@"email": self.emailTxtField.text,@"password":self.passwordTxtField.text ,@"tournament_id":selectedTournamentIndex,@"first_name":self.firstNameTxtField.text,@"sur_name":self.surnameTxtField.text  };
                //NSDictionary *params = @{@"email": @"spatel@aecrodigital.com",@"password":@"sanjay1!" ,@"tournament_id":@"1",@"first_name":@"sanjay",@"sur_name":@"patel" };
                //Configure your session with common header fields like authorization etc
                NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
                sessionConfiguration.HTTPAdditionalHeaders = @{@"IsMobileUser": @"true"};
                NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
                NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,Registration ];
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
                                                          [SVProgressHUD dismiss];
                                                          NSError *parseError = nil;
                                                          NSDictionary *responseDictionary = [NSJSONSerialization JSONObjectWithData:data options:0 error:&parseError];
                                                          //NSLog(@"%@",responseDictionary);
                                                          NSString *message = [responseDictionary valueForKey:@"message"];
//                                                          UIAlertController *alertController = [UIAlertController alertControllerWithTitle:@"ESR" message:message preferredStyle:UIAlertControllerStyleAlert];
//                                                          UIAlertAction* ok = [UIAlertAction actionWithTitle:@"OK" style:UIAlertActionStyleDefault handler:^(UIAlertAction * action) {
//                                                            
//                                                              [self.navigationController popViewControllerAnimated:TRUE];                                                          }];
//                                                          [alertController addAction:ok];
//                                                          [self presentViewController:alertController animated:YES completion:nil];
                                                          dispatch_async(dispatch_get_main_queue(), ^{
                                                              self.alertTitle.text = @"Euro-Sporing";
                                                              self.alertSubTitle.text = message;
                                                              self.alertView.hidden = FALSE;
                                                          });
                                                      }
                                                  }];
                [dataTask resume];
            }else{
                
            }
        }else{
//            UIAlertController *alertController = [UIAlertController alertControllerWithTitle:@"" message:@"Passwords do not match" preferredStyle:UIAlertControllerStyleAlert];
//            UIAlertAction* ok = [UIAlertAction actionWithTitle:@"OK" style:UIAlertActionStyleDefault handler:^(UIAlertAction * action) {
//                
//                }];
//            [alertController addAction:ok];
//            [self presentViewController:alertController animated:YES completion:nil];
            self.passwordAlertView.hidden = FALSE;
            self.passwordAlertTitle.text = @"Euro-Sportring";
            self.passwordAlertSubTitle.text = @"Passwords do not match";
        }
    }
}

- (IBAction)tournamentBtnClick:(id)sender {
    [self.firstNameTxtField resignFirstResponder];
    [self.passwordTxtField resignFirstResponder];
    [self.confirmTxtField resignFirstResponder];
    [self.surnameTxtField resignFirstResponder];
    [self.emailTxtField resignFirstResponder];
    [self.TournamentTxtField resignFirstResponder];
    [self.scroll endEditing: YES];
    SignUpTournamentList *myVC = (SignUpTournamentList *)[self.storyboard instantiateViewControllerWithIdentifier:@"SignUpTournamentList"];
    [self.navigationController pushViewController:myVC animated:YES];
}

- (IBAction)backBtnClick:(id)sender {
    [self.navigationController popViewControllerAnimated:TRUE];
}
- (IBAction)passwordAlertOkBtnClick:(id)sender {
    self.passwordAlertView.hidden = TRUE;
}
- (IBAction)alertOkBtnClick:(id)sender {
    [self.navigationController popViewControllerAnimated:TRUE];
}
@end
