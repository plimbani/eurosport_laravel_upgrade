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
#import "AppDelegate.h"
#import <QuartzCore/QuartzCore.h>
#import "UIColor+fromHex.h"
#import "LanguageManager.h"
#import "Reachability.h"
@interface UpdateProfileVC ()

@end

@implementation UpdateProfileVC
@synthesize selectedTournamentIndex;
-(UIView *)PaddingView{
    return [[UIView alloc] initWithFrame:CGRectMake(0, 0, 5, 20)];
}
-(void)GetTournamentLst{
    
    if([Utils isNetworkAvailable] ==YES){
        //        NSMutableURLRequest *request = [[NSMutableURLRequest alloc]init];
        [SVProgressHUD show];
        NSString *post = @"";
        NSData *postData = [post dataUsingEncoding:NSUTF8StringEncoding allowLossyConversion:YES];
        //        NSString *postLength = [NSString stringWithFormat:@"%lu",(unsigned long)[postData length]];
        NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,Tournaments];
        //[request setURL:[NSURL URLWithString:url]];
        NSDictionary *header =@{@"IsMobileUser": @"true"};
        NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
        sessionConfiguration.HTTPAdditionalHeaders =header;
        NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
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
                                                  //_tournamentlistArray =[responseDictionary[@"data"] mutableCopy];
                                                  _autoCompleteArray = [responseDictionary[@"data"] mutableCopy];
                                                  NSLog(@"%@",_autoCompleteArray);
                                                  dispatch_async(dispatch_get_main_queue(), ^{
                                                      [self.autoCompleteTableView reloadData];
                                                      [self.pickerView reloadAllComponents];
                                                      
                                                  });
                                                  
                                                  
                                              }
                                          }];
        [dataTask resume];
    }
    
}
-(void)GetFavouriteTournament{
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
        NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,GetUserFavouriteTournamentList];
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
                                                  _selectedArray =[responseDictionary[@"data"] mutableCopy];
                                              }
                                          }];
        [dataTask resume];
    }else{
        
    }
}
- (NSInteger )numberOfComponentsInPickerView:(UIPickerView *)pickerView
{
    return 1;
}
- (NSAttributedString *)pickerView:(UIPickerView *)pickerView attributedTitleForRow:(NSInteger)row forComponent:(NSInteger)component
{
    NSString *title;
    if (pickerView == self.pickerView) {
        title = [NSString stringWithFormat:@"%@", [[_autoCompleteArray objectAtIndex:row] valueForKey:@"name"]];
    }else{
        title = [NSString stringWithFormat:@"%@",NSLocalizedString([_autoCompleteLanguageArray objectAtIndex:row], @"")];
    }
    
    NSAttributedString *attString =
    [[NSAttributedString alloc] initWithString:title attributes:@{NSForegroundColorAttributeName:[UIColor whiteColor]}];
    
    return attString;
}
// The number of rows of data
- (NSInteger )pickerView:(UIPickerView *)pickerView numberOfRowsInComponent:(NSInteger)component
{
    if (pickerView == self.pickerView ) {
        return _autoCompleteArray.count;
    }else{
        return _autoCompleteLanguageArray.count;
    }
    
}

// The data to return for the row and component (column) that's being passed in
- (NSString*)pickerView:(UIPickerView *)pickerView titleForRow:(NSInteger)row forComponent:(NSInteger)component
{
    if (self.pickerView == pickerView) {
        return [NSString stringWithFormat:@"%@", [[_autoCompleteArray objectAtIndex:row] valueForKey:@"name"]];
    }else{
        return [NSString stringWithFormat:@"%@", [_autoCompleteLanguageArray objectAtIndex:row]];
    }
}
- (void)pickerView:(UIPickerView *)pickerView didSelectRow:(NSInteger)row inComponent:(NSInteger)component
{
    // This method is triggered whenever the user makes a change to the picker selection.
    // The parameter named row and component represents what was selected.
    if (pickerView == self.pickerView) {
        self.TournamentTxtField.text = [[_autoCompleteArray objectAtIndex:row] valueForKey:@"name"];
        selectedTournamentIndex =[[_autoCompleteArray objectAtIndex:row] valueForKey:@"id"];
    }else{
        self.languageTextField.text =NSLocalizedString([_autoCompleteLanguageArray objectAtIndex:row], @"");
        selectLanguageIndex = row;
        
//        [self.view setNeedsDisplay];
        //[self reloadRootViewController];
    }
}
- (void)reloadRootViewController
{
    AppDelegate *delegate = (AppDelegate *)[UIApplication sharedApplication].delegate;
    NSString *storyboardName = @"Main";
    UIStoryboard *storyboard = [UIStoryboard storyboardWithName:storyboardName bundle:nil];
    delegate.window.rootViewController = [storyboard instantiateInitialViewController];
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
    selectLanguageIndex = 0;
    [self GetTournamentLst];
    [self GetFavouriteTournament];
    self.surnameView.layer.borderColor = [UIColor lightGrayColor].CGColor;
    self.surnameView.layer.borderWidth = 1.0f;
    
    self.firstNameView.layer.borderColor = [UIColor lightGrayColor].CGColor;
    self.firstNameView.layer.borderWidth = 1.0f;
    
    self.emailView.layer.borderColor = [UIColor lightGrayColor].CGColor;
    self.emailView.layer.borderWidth = 1.0f;
    
//    self.tournamentView.layer.borderColor = [UIColor lightGrayColor].CGColor;
//    self.tournamentView.layer.borderWidth = 1.0f;
//    
//    self.languageView.layer.borderColor = [UIColor lightGrayColor].CGColor;
//    self.languageView.layer.borderWidth = 1.0f;
    AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
    NSLog(@"%@",app.defaultTournamentDir);
    self.TournamentTxtField.text =[app.defaultTournamentDir valueForKey:@"name"];
    _autoCompleteTableView.hidden =TRUE;
    // _autoCompleteTableView.tableFooterView = [UIView new];
    [[_autoCompleteTableView layer] setMasksToBounds:NO];
    [[_autoCompleteTableView layer] setShadowColor:[UIColor blackColor].CGColor];
    [[_autoCompleteTableView layer] setShadowOffset:CGSizeMake(0.0f, 5.0f)];
    [[_autoCompleteTableView layer] setShadowOpacity:0.3f];
    
    _autoCompleteLanguageTableView.hidden =TRUE;
    // _autoCompleteTableView.tableFooterView = [UIView new];
    [[_autoCompleteLanguageTableView layer] setMasksToBounds:NO];
    [[_autoCompleteLanguageTableView layer] setShadowColor:[UIColor blackColor].CGColor];
    [[_autoCompleteLanguageTableView layer] setShadowOffset:CGSizeMake(0.0f, 5.0f)];
    [[_autoCompleteLanguageTableView layer] setShadowOpacity:0.3f];
    
    self.pickerView.hidden = TRUE;
    self.languagePickerView.hidden = TRUE;
    _autoCompleteLanguageArray = [[NSMutableArray alloc] initWithObjects:@"English",@"French",@"Italian",@"German",@"Dutch",@"Czech",@"Danish",@"Polish", nil];
    NSArray *languageCode = [[NSArray alloc] initWithObjects:@"en",@"fr", @"it", @"de", @"nl",@"cs",@"da", @"pl", nil];
    [self.autoCompleteLanguageTableView reloadData];
    [self.languagePickerView reloadAllComponents];
    self.profileImage.layer.cornerRadius = 50;
    self.profileImage.layer.masksToBounds = true;
    // Do any additional setup after loading the view.
    self.surnameTxtField.delegate = self;
    self.emailTxtField.delegate = self;
    self.passwordTxtField.delegate = self;
    self.firstNameTxtField.delegate = self;
    self.TournamentTxtField.delegate = self;
    
//    self.surnameTxtField.borderStyle = UITextBorderStyleLine;
//    self.surnameTxtField.layer.borderWidth = 1;
//    self.surnameTxtField.layer.masksToBounds = true;
//    self.surnameTxtField.layer.borderColor = (__bridge CGColorRef _Nullable)([UIColor greenColor]);
//    
//    self.firstNameTxtField.borderStyle = UITextBorderStyleLine;
//    self.firstNameTxtField.layer.borderWidth = 1;
//    self.firstNameTxtField.layer.borderColor = (__bridge CGColorRef _Nullable)([UIColor colorwithHexString:@"96989b" alpha:1.0]);
//    
//    self.emailTxtField.borderStyle = UITextBorderStyleLine;
//    self.emailTxtField.layer.borderWidth = 1;
//    self.emailTxtField.layer.borderColor = (__bridge CGColorRef _Nullable)([UIColor colorwithHexString:@"96989b" alpha:1.0]);
//    self.emailTxtField.layer.masksToBounds = true;
//    
//    self.languageTextField.borderStyle = UITextBorderStyleLine;
//    self.languageTextField.layer.borderWidth = 1;
//    self.languageTextField.layer.borderColor = (__bridge CGColorRef _Nullable)([UIColor colorwithHexString:@"96989b" alpha:1.0]);
//    self.languageTextField.layer.masksToBounds = true;
//    self.languageTextField.clipsToBounds      = YES;
//    self.TournamentTxtField.borderStyle = UITextBorderStyleLine;
//    self.TournamentTxtField.layer.borderWidth = 1;
//    self.TournamentTxtField.layer.borderColor = (__bridge CGColorRef _Nullable)([UIColor colorwithHexString:@"96989b" alpha:1.0]);
    
    self.languageTextField.text = @"English";
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
//    if (![[userData valueForKey:@"profile_image_url"] isKindOfClass:[NSNull class]] && ![[userData valueForKey:@"profile_image_url"] isEqualToString:@""]) {
//        NSURL *url = [NSURL URLWithString:[[userData valueForKey:@"profile_image_url"] valueForKey:@"tournamentLogo"]];
//        NSURLRequest* request = [NSURLRequest requestWithURL:url];
//        [NSURLConnection sendAsynchronousRequest:request
//                                           queue:[NSOperationQueue mainQueue]
//                               completionHandler:^(NSURLResponse * response,
//                                                   NSData * data,
//                                                   NSError * error) {
//                                   if (!error){
//                                       UIImage *image = [UIImage imageWithData:data];
//                                       self.profileImage.image = image;
//                                   }
//                               }];
//    }
    self.emailTxtField.text = [userData valueForKey:@"email"];
    self.firstNameTxtField.text = [userData valueForKey:@"first_name"];
    self.surnameTxtField.text = [userData valueForKey:@"sur_name"];
    [self GetTournamentLst:[userData valueForKey:@"tournament_id"]];
    for (int i=0; i<languageCode.count; i++) {
        if ([[languageCode objectAtIndex:i] isEqualToString:[userData valueForKey:@"locale"]]) {
            self.languageTextField.text = NSLocalizedString([_autoCompleteLanguageArray objectAtIndex:i],@"" );
        }
    }
    UIGestureRecognizer *gestureRecognizer = [[UIGestureRecognizer alloc] init];
    gestureRecognizer.delegate = self;
    [self.scrollSubView addGestureRecognizer:gestureRecognizer];
    self.autoCompleteTableView.tag = 1;
    self.autoCompleteLanguageTableView.tag = 2;
    UITapGestureRecognizer *tapAction = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(lblClick:)];
    tapAction.delegate =self;
    tapAction.numberOfTapsRequired = 1;
    
    //Enable the lable UserIntraction
    _titleLbl.userInteractionEnabled = YES;
    [_titleLbl addGestureRecognizer:tapAction];
}
- (void)reachabilityChanged:(NSNotification*)notification1
{
    Reachability* reachability = notification1.object;
    if(reachability.currentReachabilityStatus == NotReachable)
        self.offlineView.hidden = false;
    else
        self.offlineView.hidden = TRUE;
}

- (void)lblClick:(UITapGestureRecognizer *)tapGesture {
    [self.navigationController popViewControllerAnimated:TRUE];
}
- (BOOL) gestureRecognizer:(UIGestureRecognizer *)gestureRecognizer shouldReceiveTouch:(UITouch *)touch
{
    
    UIView* view = gestureRecognizer.view;
    CGPoint loc = [gestureRecognizer locationInView:_scrollSubView];
    UIView* subview = [view hitTest:loc withEvent:nil];
    if ([touch.view isKindOfClass:[UITextField class]])
    {
        self.autoCompleteTableView.hidden = TRUE;
        self.autoCompleteLanguageTableView.hidden = TRUE;
        self.pickerView.hidden = TRUE;
        self.languagePickerView.hidden = TRUE;
        return FALSE;
    }else if([touch.view.superview isKindOfClass:[UITableViewCell class]]) {
        UITableView *tableView = (UITableView *)touch.view.superview.superview.superview;
        [self.firstNameTxtField resignFirstResponder];
        [self.passwordTxtField resignFirstResponder];
        [self.surnameTxtField resignFirstResponder];
        [self.emailTxtField resignFirstResponder];
        [self.TournamentTxtField resignFirstResponder];
        [self.scroll endEditing: YES];
        [self.scrollSubView endEditing:YES];
        if (tableView.tag == 1) {
            CGPoint loc = [touch locationInView:_autoCompleteTableView];
            NSIndexPath *path = [_autoCompleteTableView indexPathForRowAtPoint:loc];
            //NSLog(@"%ld",path.row);
            self.TournamentTxtField.text = [[_autoCompleteArray objectAtIndex:path.row] valueForKey:@"name"];
            selectedTournamentIndex =[[_autoCompleteArray objectAtIndex:path.row] valueForKey:@"id"];
        }else{
            CGPoint loc = [touch locationInView:_autoCompleteLanguageTableView];
            NSIndexPath *path = [_autoCompleteLanguageTableView indexPathForRowAtPoint:loc];
            self.languageTextField.text =NSLocalizedString([_autoCompleteLanguageArray objectAtIndex:path.row], @"");
        }
        self.autoCompleteTableView.hidden = TRUE;
        self.autoCompleteLanguageTableView.hidden = TRUE;
        return TRUE;
    }if ([touch.view isKindOfClass:[UILabel class]])
    {
        [self.navigationController popViewControllerAnimated:TRUE];
        return FALSE;
    }
    else
    {
        [self.firstNameTxtField resignFirstResponder];
        [self.passwordTxtField resignFirstResponder];
        [self.surnameTxtField resignFirstResponder];
        [self.emailTxtField resignFirstResponder];
        [self.TournamentTxtField resignFirstResponder];
        [self.scroll endEditing: YES];
        [self.scrollSubView endEditing:YES];
        self.autoCompleteTableView.hidden = TRUE;
        self.autoCompleteLanguageTableView.hidden = TRUE;
        self.pickerView.hidden = TRUE;
        self.languagePickerView.hidden = TRUE;
        // here is remove keyBoard code
        return FALSE;
    }

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
    //[self scrollToY:0];
}
- (void)viewWillAppear:(BOOL)animated
{
    [super viewWillAppear:animated];
    //[self scrollToY:0];
    self.titleLbl.text = [NSLocalizedString(@"Profile", @"") uppercaseString];
    if (self.firstNameTxtField.text.length > 0 && self.surnameTxtField.text.length >0 && self.emailTxtField.text.length >0 ) {
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
    if([Utils isNetworkAvailable] ==YES){
        self.offlineView.hidden = TRUE;
    }else{
        self.offlineView.hidden = false;
    }
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
-(NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section{
    if (tableView == _autoCompleteTableView) {
        return [_autoCompleteArray count];
    }else{
        return [_autoCompleteLanguageArray count];
    }
    return [_autoCompleteArray count];
}
-(CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(nonnull NSIndexPath *)indexPath
{
    return 35;
}
-(UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath{
    //    TournamentListCell *cell = (TournamentListCell*)[tableView dequeueReusableCellWithIdentifier:@"TournamentListCell"];
    //    cell.lbl.text = [[_autoCompleteArray objectAtIndex:indexPath.row] valueForKey:@"name"];
    //    return cell;
    UITableViewCell *Cell = [tableView dequeueReusableHeaderFooterViewWithIdentifier:nil];
    if (Cell ==nil) {
        Cell = [[UITableViewCell alloc]initWithStyle:UITableViewCellStyleDefault reuseIdentifier:nil];
    }
    if (tableView == _autoCompleteTableView) {
        Cell.textLabel.text = [NSString stringWithFormat:@"%@", [[_autoCompleteArray objectAtIndex:indexPath.row] valueForKey:@"name"]];
    }else{
        Cell.textLabel.text = [NSString stringWithFormat:@"%@", NSLocalizedString([_autoCompleteLanguageArray objectAtIndex:indexPath.row], @"")];
    }
    
    Cell.contentView.backgroundColor = [UIColor whiteColor];
    Cell.backgroundColor = [UIColor clearColor];
    return Cell;
}

-(void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath{
    UITableViewCell *Cell = [tableView cellForRowAtIndexPath:indexPath];
    if (tableView == _autoCompleteTableView) {
        self.autoCompleteTableView.hidden = TRUE;
        self.TournamentTxtField.text = [[_autoCompleteArray objectAtIndex:indexPath.row] valueForKey:@"name"];
        selectedTournamentIndex =[[_autoCompleteArray objectAtIndex:indexPath.row] valueForKey:@"id"];
    }else{
        self.autoCompleteLanguageTableView.hidden = TRUE;
        self.languageTextField.text =NSLocalizedString([_autoCompleteLanguageArray objectAtIndex:indexPath.row], @"");
    }
    
    //[self.scrollSubView addGestureRecognizer:tap];
}
- (BOOL)textField:(UITextField *)textField shouldChangeCharactersInRange:(NSRange)range replacementString:(NSString *)string
{
    
    if (self.firstNameTxtField.text.length > 0 && self.surnameTxtField.text.length >0 && self.emailTxtField.text.length >0   && self.TournamentTxtField.text.length >0) {
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
    //[self scrollToView:textField];
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
    if (self.firstNameTxtField.text.length > 0 && self.surnameTxtField.text.length >0 && self.emailTxtField.text.length >0 ) {
        self.signUpBtn.enabled = TRUE;
        self.signUpBtn.backgroundColor =[UIColor colorwithHexString:@"ED9E2D" alpha:1.0];
    }else{
        self.signUpBtn.enabled = FALSE;
        self.signUpBtn.backgroundColor =[UIColor colorwithHexString:@"CCCCCC" alpha:1.0];
    }
    
}
//- (void)scrollViewWillBeginDragging:(UIScrollView *)scrollView
//{
//    [self.firstNameTxtField resignFirstResponder];
//    [self.passwordTxtField resignFirstResponder];
//    [self.surnameTxtField resignFirstResponder];
//    [self.emailTxtField resignFirstResponder];
//    [self.TournamentTxtField resignFirstResponder];
//    [self.scroll endEditing: YES];
//}
//- (void)scrollToY:(float)y
//{
//    [UIView beginAnimations:@"registerScroll" context:NULL];
//    [UIView setAnimationCurve:UIViewAnimationCurveEaseInOut];
//    [UIView setAnimationDuration:0.4];
//    self.view.transform = CGAffineTransformMakeTranslation(0, y);
//    [UIView commitAnimations];
//}
//
//- (void)scrollToView:(UIView*)view
//{
//    CGRect theFrame = view.frame;
//    float y = theFrame.origin.y + theFrame.size.height +0;
//    y -= (y / 1.7);
//    [self scrollToY:-y];
//}
//
//- (void)scrollElement:(UIView*)view toPoint:(float)y
//{
//    CGRect theFrame = view.frame;
//    float orig_y = theFrame.origin.y;
//    float diff = y - orig_y;
//    if (diff < 0) {
//        [self scrollToY:diff];
//    }
//    else {
//        [self scrollToY:0];
//    }
//}

- (void)keyboardWillHide:(NSNotification*)notification
{
   // [self scrollToY:0];
}
/*
 #pragma mark - Navigation
 
 // In a storyboard-based application, you will often want to do a little preparation before navigation
 - (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
 // Get the new view controller using [segue destinationViewController].
 // Pass the selected object to the new view controller.
 }
 */
-(void)setDefaultToutnamet{
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    NSDictionary *userData = [defaults objectForKey:@"userData"];
    if([Utils isNetworkAvailable] ==YES){
        [SVProgressHUD show];
        NSDictionary *params = @{@"user_id":[userData valueForKey:@"user_id"],@"tournament_id": [userData valueForKey:@"tournament_id"]};
        NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
        NSString *token = [defaults objectForKey:@"token"];
        NSString *concateToken = [NSString stringWithFormat:@"%@%@",@"Bearer ",token];
        NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
        NSDictionary *header =@{@"IsMobileUser": @"true",@"Authorization":concateToken};
        sessionConfiguration.HTTPAdditionalHeaders = header;
        NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
        NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,SetTournamentDefault];
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
                                                  //_selectedArray =[responseDictionary[@"data"] mutableCopy];
                                                  dispatch_async(dispatch_get_main_queue(), ^{
                                                  });
                                                  
                                              }
                                          }];
        [dataTask resume];
    }else{
        
    }
}
-(void)addFavourite:(NSString *)tournamentID{
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    NSDictionary *userData = [defaults objectForKey:@"userData"];
    if([Utils isNetworkAvailable] ==YES){
        [SVProgressHUD show];
        NSDictionary *params = @{@"user_id":[userData valueForKey:@"user_id"],@"tournament_id":tournamentID };
        NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
        NSString *token = [defaults objectForKey:@"token"];
        NSString *concateToken = [NSString stringWithFormat:@"%@%@",@"Bearer ",token];
        NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
        NSDictionary *header =@{@"IsMobileUser": @"true",@"Authorization":concateToken};
        sessionConfiguration.HTTPAdditionalHeaders = header;
        NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
        NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,MakeTournamentFavourite];
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
                                                      [self setDefaultToutnamet];
                                                });
                                                  
                                              }
                                          }];
        [dataTask resume];
    }else{
        
    }
}

- (IBAction)signUpBtnClick:(id)sender {
    if (self.firstNameTxtField.text.length > 0 && self.surnameTxtField.text.length >0 && self.emailTxtField.text.length >0  && self.TournamentTxtField.text.length >0) {
        if([Utils isNetworkAvailable] ==YES){
            [SVProgressHUD show];
            NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
            NSDictionary *userData = [[defaults objectForKey:@"userData"] mutableCopy];
            NSArray *languageCode = [[NSArray alloc] initWithObjects:@"en",@"fr", @"it", @"de", @"nl",@"cs",@"da", @"pl", nil];
            NSDictionary *params = @{@"user_id":[userData valueForKey:@"user_id"],@"tournament_id":[NSString stringWithFormat:@"%@",selectedTournamentIndex],@"first_name":self.firstNameTxtField.text,@"last_name":self.surnameTxtField.text,@"locale":[languageCode objectAtIndex:selectLanguageIndex]};
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
                                                      NSString *message = [responseDictionary valueForKey:@"message"];
                                                      dispatch_async(dispatch_get_main_queue(), ^{
                                                          [LanguageManager saveLanguageByIndex:selectLanguageIndex];
                                                          //[self setDefaultToutnamet];
                                                          if ([message isEqualToString:@"This email already exists."]) {
                                                              self.alertViewTitle.text = NSLocalizedString(@"Error",@"" );
                                                              self.alertViewSubTitle.text = NSLocalizedString(message,@"" );
                                                          }else{
                                                              self.alertViewTitle.text = NSLocalizedString(@"Confirmation",@"" );
                                                              self.alertViewSubTitle.text = NSLocalizedString(message,@"" );
                                                          }
                                                          
                                                          self.alertView.hidden = FALSE;
                                                          [[self.tabBarController.tabBar.items objectAtIndex:0]setTitle:NSLocalizedString(@"Favourites", @"")];
                                                          [[self.tabBarController.tabBar.items objectAtIndex:1]setTitle:NSLocalizedString(@"Tournament", @"")];
                                                          [[self.tabBarController.tabBar.items objectAtIndex:2]setTitle:NSLocalizedString(@"Teams", @"")];
                                                          [[self.tabBarController.tabBar.items objectAtIndex:3]setTitle:NSLocalizedString(@"Age categories", @"")];
                                                          [[self.tabBarController.tabBar.items objectAtIndex:4]setTitle:NSLocalizedString(@"Settings", @"")];
                                                          [userData setValue:self.firstNameTxtField.text forKey:@"first_name"];
                                                          [userData setValue:self.surnameTxtField.text  forKey:@"sur_name"];
                                                          [userData setValue:[NSString stringWithFormat:@"%@",selectedTournamentIndex] forKey:@"tournament_id"];
                                                          [userData setValue:[NSString stringWithFormat:@"%@",[languageCode objectAtIndex:selectLanguageIndex]]  forKey:@"locale"];
                                                          [[NSUserDefaults standardUserDefaults] setObject:userData forKey:@"userData"];
                                                          [[NSUserDefaults standardUserDefaults] synchronize];
                                                          int flag =0;
                                                          for (int i=0; i<_selectedArray.count;i++) {
                                                              if ([selectedTournamentIndex integerValue] ==[[[_selectedArray objectAtIndex:i] valueForKey:@"tournament_id"] integerValue]) {
                                                                  flag =1;
                                                                  break;
                                                              }
                                                          }
                                                          if (flag == 0) {
                                                              [self addFavourite:selectedTournamentIndex];
                                                              //    [_selectedArray addObject:defaultTournamentDir];
                                                              
                                                          }else{
                                                              [self setDefaultToutnamet];
                                                          }
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
//    SignUpTournamentList *myVC = (SignUpTournamentList *)[self.storyboard instantiateViewControllerWithIdentifier:@"SignUpTournamentList"];
//    [self.navigationController pushViewController:myVC animated:YES];
    //self.autoCompleteTableView.hidden= FALSE;
    self.languagePickerView.hidden = TRUE;
    self.pickerView.hidden = FALSE;
    
}

- (IBAction)languageBtnClick:(id)sender {
    [self.firstNameTxtField resignFirstResponder];
    [self.passwordTxtField resignFirstResponder];
    [self.surnameTxtField resignFirstResponder];
    [self.emailTxtField resignFirstResponder];
    [self.TournamentTxtField resignFirstResponder];
    [self.scroll endEditing: YES];
//    LanguageVC *myVC = (LanguageVC *)[self.storyboard instantiateViewControllerWithIdentifier:@"LanguageVC"];
//    [self.navigationController pushViewController:myVC animated:YES];
    //self.autoCompleteLanguageTableView.hidden= FALSE;
    self.languagePickerView.hidden = FALSE;
    self.pickerView.hidden = TRUE;
    
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
