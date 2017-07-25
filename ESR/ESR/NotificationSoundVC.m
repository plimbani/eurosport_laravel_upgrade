//
//  NotificationSoundVC.m
//  ESR
//
//  Created by Aecor Digital on 10/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "NotificationSoundVC.h"
#import "Globals.h"
#import "Utils.h"
#import "SVProgressHUD.h"
#import "AppDelegate.h"

@interface NotificationSoundVC ()

@end

@implementation NotificationSoundVC

- (void)viewDidLoad {
    [super viewDidLoad];
    [self getSetting];
    // Do any additional setup after loading the view.
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
-(void)getSetting{
    if([Utils isNetworkAvailable] ==YES){
        [SVProgressHUD show];
        NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
        NSDictionary *userData = [defaults objectForKey:@"userData"];
        NSDictionary *params = @{@"user_id":[userData valueForKey:@"user_id"]  };
        NSString *token = [defaults objectForKey:@"token"];
        NSString *concateToken = [NSString stringWithFormat:@"%@%@",@"Bearer ",token];
        NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
        NSDictionary *header =@{@"IsMobileUser": @"true",@"Authorization":concateToken};
        sessionConfiguration.HTTPAdditionalHeaders = header;
        NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
        NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,GetSetting];
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
                                                   NSString *jsonString = [[[responseDictionary valueForKey:@"data"] objectAtIndex:0] valueForKey:@"value"];
                                                  NSData *data1 = [jsonString dataUsingEncoding:NSUTF8StringEncoding];
                                                  id responseDictionary1 = [NSJSONSerialization JSONObjectWithData:data1 options:0 error:nil];
                                                  NSLog(@"%@",[responseDictionary1 valueForKey:@"is_sound"]);
                                                  if ([[responseDictionary1 valueForKey:@"is_sound"] isEqualToString:@"true"]) {
                                                      [_soundSwitch setOn:YES animated:YES];
                                                      sound = @"true";
                                                  }else{
                                                      sound = @"false";
                                                      [_soundSwitch setOn:NO animated:YES];
                                                  }
                                                  if ([[responseDictionary1 valueForKey:@"is_vibration"] isEqualToString:@"true"]) {
                                                      [_vibrationSwitch setOn:YES animated:YES];
                                                      vibration = @"true";
                                                  }else{
                                                      vibration = @"false";
                                                      [_vibrationSwitch setOn:NO animated:YES];
                                                  }
                                                  if ([[responseDictionary1 valueForKey:@"is_notification"] isEqualToString:@"true"]) {
                                                      notification = @"true";
                                                      [_notificationSwitch setOn:YES animated:YES];
                                                  }else{
                                                      notification = @"false";
                                                      [_notificationSwitch setOn:NO animated:YES];
                                                  }
                                                  
                                              }
                                          }];
        [dataTask resume];
    }else{
        
    }
}
- (IBAction)soundValueChange:(id)sender {
    if(_soundSwitch.isOn)
    {
        sound = @"true";
        NSLog(@"The switch is on");
    }
    else
    {
        sound = @"false";
        NSLog(@"The switch is off");
    }
}

- (IBAction)vibrationValueChange:(id)sender {
    if(_vibrationSwitch.isOn)
    {
        vibration = @"true";
        NSLog(@"The switch is on");
    }
    else
    {
        vibration = @"false";
        NSLog(@"The switch is off");
    }
}

- (IBAction)notificationValueChange:(id)sender {
    if(_notificationSwitch.isOn)
    {
        notification = @"true";
        NSLog(@"The switch is on");
    }
    else
    {
        notification = @"false";
        NSLog(@"The switch is off");
    }
}
-(void)updateSetting{
    if([Utils isNetworkAvailable] ==YES){
        [SVProgressHUD show];
        NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
        NSDictionary *userData = [defaults objectForKey:@"userData"];
        NSDictionary *dataDir = @{@"is_sound":sound,@"is_vibration":vibration,@"is_notification":notification};
        NSDictionary *userSettings = @{@"userSettings":dataDir };
        NSDictionary *mainDir =@{@"userId":[userData valueForKey:@"user_id"] ,@"userSettings":userSettings};
        NSDictionary *params = @{@"userData":mainDir };
        NSString *token = [defaults objectForKey:@"token"];
        NSString *concateToken = [NSString stringWithFormat:@"%@%@",@"Bearer ",token];
        NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
        NSDictionary *header =@{@"IsMobileUser": @"true",@"Authorization":concateToken};
        sessionConfiguration.HTTPAdditionalHeaders = header;
        NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
        NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,GetMatchFixturesClubID];
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
    }else{
        
    }
}
- (IBAction)backBtnClick:(id)sender {
    [self updateSetting];
    [self.navigationController popViewControllerAnimated:TRUE];
}
@end
