//
//  UpdateProfileVC.m
//  ESR
//
//  Created by Aecor Digital on 27/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "UpdateProfileVC.h"
#import <AFNetworking/AFNetworking.h>
#import "LoginVC.h"
#import "Globals.h"
#import "Utils.h"
#import "SignUpTournamentList.h"
#import "UIColor+fromHex.h"
#import "SVProgressHUD.h"
#import "LanguageVC.h"

@interface UpdateProfileVC ()

@end

@implementation UpdateProfileVC
@synthesize selectedTournamentIndex;
-(UIView *)PaddingView{
    return [[UIView alloc] initWithFrame:CGRectMake(0, 0, 5, 20)];
}
-(void)GetTournamentLst:(NSString *)tournamentID{
    if([Utils isNetworkAvailable] ==YES){
        //NSDictionary *params = @{@"user_email": userEmail, @"imei": imei};
        NSDictionary *params = @"";
        AFHTTPRequestOperationManager *manager = [AFHTTPRequestOperationManager manager];
        NSString *finalURL=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,Tournaments ];
        manager.responseSerializer = [AFJSONResponseSerializer serializer];
        [manager GET:finalURL parameters:params success:^(AFHTTPRequestOperation *operation, id responseObject) {
            NSLog(@"JSON: %@", responseObject);
            NSMutableArray *list = [responseObject[@"data"] mutableCopy];
            for (int i=0; i<list.count; i++) {
                if ([[[list objectAtIndex:i] valueForKey:@"id"] integerValue] ==[tournamentID integerValue]) {
                    self.TournamentTxtField.text = [[list objectAtIndex:i] valueForKey:@"name"];
                    self.selectedTournamentIndex = [NSString stringWithFormat:@"%@",[[list objectAtIndex:i] valueForKey:@"id"]];
                }
            }
            //NSDictionary *data =  responseObject[@"data"];
            //_tournamentlistArray =[responseObject[@"data"] mutableCopy];
            
        } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
            //NSString *myString = [[NSString alloc] initWithData:operation.request.HTTPBody encoding:NSUTF8StringEncoding];
            //NSLog(@"Error: %@",myString);
        }];
    }else{
        
    }
    
}
- (void)viewDidLoad {
    [super viewDidLoad];
    self.profileImage.layer.cornerRadius = 50;
    self.profileImage.layer.masksToBounds = true;
    // Do any additional setup after loading the view.
    self.surnameTxtField.delegate = self;
    self.emailTxtField.delegate = self;
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
    self.TournamentTxtField.leftView = [self PaddingView];
    self.TournamentTxtField.leftViewMode = UITextFieldViewModeAlways;
    self.languageTextField.leftView = [self PaddingView];
    self.languageTextField.leftViewMode = UITextFieldViewModeAlways;
    UIColor *color = [UIColor grayColor];
    self.surnameTxtField.attributedPlaceholder = [[NSAttributedString alloc] initWithString:@"Surname" attributes:@{NSForegroundColorAttributeName: color}];
    self.emailTxtField.attributedPlaceholder = [[NSAttributedString alloc] initWithString:@"Email" attributes:@{NSForegroundColorAttributeName: color}];
    self.passwordTxtField.attributedPlaceholder = [[NSAttributedString alloc] initWithString:@"Password" attributes:@{NSForegroundColorAttributeName: color}];
    self.firstNameTxtField.attributedPlaceholder = [[NSAttributedString alloc] initWithString:@"First name" attributes:@{NSForegroundColorAttributeName: color}];
    self.TournamentTxtField.attributedPlaceholder = [[NSAttributedString alloc] initWithString:@"Tournament" attributes:@{NSForegroundColorAttributeName: color}];
    self.TournamentTxtField.attributedPlaceholder = [[NSAttributedString alloc] initWithString:@"Language" attributes:@{NSForegroundColorAttributeName: color}];
    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(tournamentSelect:)
                                                 name:@"tournamentSelect" object:nil];
    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(languageSelect:)
                                                 name:@"languageSelect" object:nil];
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    NSDictionary *userData = [defaults objectForKey:@"userData"];
    if (![[userData valueForKey:@"profile_image_url"] isKindOfClass:[NSNull class]] && ![[userData valueForKey:@"profile_image_url"] isEqualToString:@""]) {
        NSURL *url = [NSURL URLWithString:[[userData valueForKey:@"profile_image_url"] valueForKey:@"tournamentLogo"]];
        NSURLRequest* request = [NSURLRequest requestWithURL:url];
        [NSURLConnection sendAsynchronousRequest:request
                                           queue:[NSOperationQueue mainQueue]
                               completionHandler:^(NSURLResponse * response,
                                                   NSData * data,
                                                   NSError * error) {
                                   if (!error){
                                       UIImage *image = [UIImage imageWithData:data];
                                       self.profileImage.image = image;
                                   }
                               }];
    }
    self.emailTxtField.text = [userData valueForKey:@"email"];
    self.firstNameTxtField.text = [userData valueForKey:@"first_name"];
    self.surnameTxtField.text = [userData valueForKey:@"sur_name"];
    [self GetTournamentLst:[userData valueForKey:@"tournament_id"]];
}
- (void)tournamentSelect:(NSNotification *)message {
    self.TournamentTxtField.text = [message.object valueForKey:@"Name"];
    selectedTournamentIndex =[message.object valueForKey:@"index"];
    NSLog(@"%@",selectedTournamentIndex);
    
}
- (void)languageSelect:(NSNotification *)message {
    self.languageTextField.text = [message.object valueForKey:@"Name"];
    //selectedTournamentIndex =[message.object valueForKey:@"index"];
    //NSLog(@"%@",selectedTournamentIndex);
    
}
-(void)hideKeyboard
{
    [self scrollToY:0];
}
- (void)viewWillAppear:(BOOL)animated
{
    [super viewWillAppear:animated];
    [self scrollToY:0];
    if (self.firstNameTxtField.text.length > 0 && self.surnameTxtField.text.length >0 && self.emailTxtField.text.length >0 && self.languageTextField.text.length >0 && self.TournamentTxtField.text.length >0) {
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
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}
- (BOOL)textField:(UITextField *)textField shouldChangeCharactersInRange:(NSRange)range replacementString:(NSString *)string
{
    
    if (self.firstNameTxtField.text.length > 0 && self.surnameTxtField.text.length >0 && self.emailTxtField.text.length >0 && self.passwordTxtField.text.length >0  && self.TournamentTxtField.text.length >0) {
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
        [self.passwordTxtField becomeFirstResponder];
    }
    else if (textField == self.emailTxtField) {
        [self.passwordTxtField becomeFirstResponder];
    }
    else if (textField == self.passwordTxtField) {
        [self.view endEditing:YES];
    }
    
}
- (void)scrollViewWillBeginDragging:(UIScrollView *)scrollView
{
    [self.firstNameTxtField resignFirstResponder];
    [self.passwordTxtField resignFirstResponder];
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
    if (self.firstNameTxtField.text.length > 0 && self.surnameTxtField.text.length >0 && self.emailTxtField.text.length >0  && self.TournamentTxtField.text.length >0) {
        if([Utils isNetworkAvailable] ==YES){
            [SVProgressHUD show];
            NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
            NSDictionary *userData = [defaults objectForKey:@"userData"];
            NSLog(@"%@",selectedTournamentIndex);
            NSDictionary *params = @{@"user_id":[userData valueForKey:@"user_id"],@"tournament_id":[NSString stringWithFormat:@"%@",selectedTournamentIndex],@"first_name":self.firstNameTxtField.text,@"last_name":self.surnameTxtField.text,@"locale":@"en"};
            NSString *token = [defaults objectForKey:@"token"];
            NSString *concateToken = [NSString stringWithFormat:@"%@%@",@"Bearer ",token];
            NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
            NSDictionary *header =@{@"IsMobileUser": @"true",@"Authorization":concateToken};
            sessionConfiguration.HTTPAdditionalHeaders = header;
            NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
            NSString *url=[[NSString alloc]initWithFormat:@"%@%@%@", BaseURL,UpdateProfile,[userData valueForKey:@"user_id"]];
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
                                                      dispatch_async(dispatch_get_main_queue(), ^{
                                                          self.alertViewTitle.text = @"Euro-Sporing";
                                                          self.alertViewSubTitle.text = message;
                                                          self.alertView.hidden = FALSE;
                                                      });
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

- (IBAction)tournamentBtnClick:(id)sender {
    [self.firstNameTxtField resignFirstResponder];
    [self.passwordTxtField resignFirstResponder];
    [self.surnameTxtField resignFirstResponder];
    [self.emailTxtField resignFirstResponder];
    [self.TournamentTxtField resignFirstResponder];
    [self.scroll endEditing: YES];
    SignUpTournamentList *myVC = (SignUpTournamentList *)[self.storyboard instantiateViewControllerWithIdentifier:@"SignUpTournamentList"];
    [self.navigationController pushViewController:myVC animated:YES];
}

- (IBAction)languageBtnClick:(id)sender {
    [self.firstNameTxtField resignFirstResponder];
    [self.passwordTxtField resignFirstResponder];
    [self.surnameTxtField resignFirstResponder];
    [self.emailTxtField resignFirstResponder];
    [self.TournamentTxtField resignFirstResponder];
    [self.scroll endEditing: YES];
    LanguageVC *myVC = (LanguageVC *)[self.storyboard instantiateViewControllerWithIdentifier:@"LanguageVC"];
    [self.navigationController pushViewController:myVC animated:YES];
}

- (IBAction)cameraBtnClick:(id)sender {
    UIAlertController *actionSheet = [UIAlertController alertControllerWithTitle:@"Please select from the options below:" message:@"" preferredStyle:UIAlertControllerStyleActionSheet];
    
    [actionSheet addAction:[UIAlertAction actionWithTitle:@"Select photo from library" style:UIAlertActionStyleDefault handler:^(UIAlertAction *action) {
        UIImagePickerController *imagePickerView = [[UIImagePickerController alloc] init];
        imagePickerView.allowsEditing = YES;
        imagePickerView.delegate = self;
        [imagePickerView setSourceType:UIImagePickerControllerSourceTypePhotoLibrary];
        [self presentViewController:imagePickerView animated:true completion:nil];
        
    }]];
    
    UIAlertAction *takePhoto =[UIAlertAction actionWithTitle:@"Take photo from camera" style:UIAlertActionStyleDefault handler:^(UIAlertAction *action) {
        if ([UIImagePickerController isSourceTypeAvailable:UIImagePickerControllerSourceTypeCamera]) {
            UIImagePickerController *pickerView =[[UIImagePickerController alloc]init];
            pickerView.allowsEditing = YES;
            pickerView.delegate = self;
            pickerView.sourceType = UIImagePickerControllerSourceTypeCamera;
            //[self presentModalViewController:pickerView animated:true];
            [self presentViewController:pickerView animated:true completion:nil];
        }
    }];
    [actionSheet addAction:takePhoto];
    [takePhoto setValue:[UIColor redColor] forKey:@"titleTextColor"];
    [actionSheet addAction:[UIAlertAction
                            actionWithTitle:@"Cancel"
                            style:UIAlertActionStyleCancel
                            handler:^(UIAlertAction * action)
                            {
                            }]];
    // Present action sheet.
    [self presentViewController:actionSheet animated:YES completion:nil];
}

- (IBAction)backBtnClick:(id)sender {
    [self.navigationController popViewControllerAnimated:TRUE];
}

- (IBAction)alertViewOkBtnClick:(id)sender {
    [self.navigationController popViewControllerAnimated:TRUE];
}
- (NSString *)encodeToBase64String:(UIImage *)image {
    return [UIImagePNGRepresentation(image) base64EncodedStringWithOptions:NSDataBase64Encoding64CharacterLineLength];
}
-(void)imagePickerController:(UIImagePickerController *)picker didFinishPickingMediaWithInfo:(NSDictionary<NSString *, id> *)info {
    [self dismissViewControllerAnimated:true completion:nil];
    
    UIImage * img = [info valueForKey:UIImagePickerControllerEditedImage];
    self.profileImage.image = img;
    NSString *imageStr = [NSString stringWithFormat:@"%@%@",@"data:image/png;base64,",[self encodeToBase64String:img]];
    [self updatePhoto:imageStr];

}
-(void)updatePhoto:(NSString *)imageStr{
    if([Utils isNetworkAvailable] ==YES){
        [SVProgressHUD show];
        NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
        NSDictionary *userData = [defaults objectForKey:@"userData"];
        NSDictionary *params = @{@"user_id":[userData valueForKey:@"user_id"],@"user_image" :imageStr};
        NSString *token = [defaults objectForKey:@"token"];
        NSString *concateToken = [NSString stringWithFormat:@"%@%@",@"Bearer ",token];
        NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
        NSDictionary *header =@{@"IsMobileUser": @"true",@"Authorization":concateToken};
        sessionConfiguration.HTTPAdditionalHeaders = header;
        NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
        NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,UpdateProfileImage];
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
                                                  NSLog(@"%@",[responseDictionary[@"data"] mutableCopy]);
                                              }
                                          }];
        [dataTask resume];
    }else{
        
    }
}
/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/

@end
