//
//  SignUpTournamentList.m
//  ESR
//
//  Created by Aecor Digital on 20/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "SignUpTournamentList.h"
#import <AFNetworking/AFNetworking.h>
#import "Globals.h"
#import "Utils.h"
#import "TournamentListCell.h"
#import "SVProgressHUD.h"

@interface SignUpTournamentList ()

@end

@implementation SignUpTournamentList
-(void)GetTournamentLst{
//    if([Utils isNetworkAvailable] ==YES){
//        //NSDictionary *params = @{@"user_email": userEmail, @"imei": imei};
//        NSDictionary *params = @"";
//        AFHTTPRequestOperationManager *manager = [AFHTTPRequestOperationManager manager];
//        NSString *finalURL=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,Tournaments ];
//        manager.responseSerializer = [AFJSONResponseSerializer serializer];
//        [manager GET:finalURL parameters:params success:^(AFHTTPRequestOperation *operation, id responseObject) {
//            NSLog(@"JSON: %@", responseObject);
//            //NSDictionary *data =  responseObject[@"data"];
//            _tournamentlistArray =[responseObject[@"data"] mutableCopy];
//            [self.tableView reloadData];
//            //            NSDictionary *userdata = data[@"user"];
//            //            NSDictionary *meta = responseObject[@"meta"];
//            
//        } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
//            //NSString *myString = [[NSString alloc] initWithData:operation.request.HTTPBody encoding:NSUTF8StringEncoding];
//            //NSLog(@"Error: %@",myString);
//        }];
//    }else{
//        
//    }
    
    
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
                                                  _tournamentlistArray =[responseDictionary[@"data"] mutableCopy];
                                                  NSLog(@"%@",_tournamentlistArray);
                                                  dispatch_async(dispatch_get_main_queue(), ^{
                                                      [self.tableView reloadData];
                                                  });
                                              }
                                          }];
        [dataTask resume];
    }
    
}
- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view.
    [self GetTournamentLst];
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
- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath{
    
    return 50 ;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    //NSArray *option=[[screen objectForKey:@"options"] objectForKey:@"optionList"];
    return _tournamentlistArray.count;
}
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    
    TournamentListCell *cell = (TournamentListCell*)[tableView dequeueReusableCellWithIdentifier:@"TournamentListCell"];
    cell.lbl.text = [[_tournamentlistArray objectAtIndex:indexPath.row] valueForKey:@"name"];
    return cell;
}
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    NSDictionary *obj = @{@"index":[[_tournamentlistArray objectAtIndex:indexPath.row] valueForKey:@"id"],@"Name":[[_tournamentlistArray objectAtIndex:indexPath.row] valueForKey:@"name"]};
    [[NSNotificationCenter defaultCenter] postNotificationName:@"tournamentSelect" object:obj];
    [self.navigationController popViewControllerAnimated:TRUE];
    
}
- (IBAction)backBtnClick:(id)sender {
    [self.navigationController popViewControllerAnimated:TRUE];
}
@end
