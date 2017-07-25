//
//  MatchDetailVC.m
//  ESR
//
//  Created by Aecor Digital on 03/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "MatchDetailVC.h"
#import "VenueDetailVC.h"
#import "MapVC.h"

@interface MatchDetailVC ()

@end

@implementation MatchDetailVC
@synthesize matchDetails;

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view.
    self.mainView.layer.borderColor = [UIColor lightGrayColor].CGColor;
    self.mainView.layer.borderWidth = 2.0f;
    self.homeTeamScore.text = [NSString stringWithFormat:@"%@",[matchDetails valueForKey:@"homeScore"]];
    self.awayTeamScore.text = [NSString stringWithFormat:@"%@",[matchDetails valueForKey:@"AwayScore"]];
    self.homeTeamName.text =[matchDetails valueForKey:@"HomeTeam"];
    self.awayTeamName.text =[matchDetails valueForKey:@"AwayTeam"];
    self.homeTeamCountry.text=[NSString stringWithFormat:@"%@",[matchDetails valueForKey:@"HomeCountryName"]];
    self.awayTeamCountry.text=[NSString stringWithFormat:@"%@",[matchDetails valueForKey:@"AwayCountryName"]];
    self.dateTime.text =[matchDetails valueForKey:@"match_datetime"];
    self.groupName.text =[matchDetails valueForKey:@"competation_name"];
    self.matchId.text =[NSString stringWithFormat:@"%@",[matchDetails valueForKey:@"match_number"]];
    NSURL *url = [NSURL URLWithString:[matchDetails valueForKey:@"AwayFlagLogo"]];
    NSURLRequest* request = [NSURLRequest requestWithURL:url];
    [NSURLConnection sendAsynchronousRequest:request
                                       queue:[NSOperationQueue mainQueue]
                           completionHandler:^(NSURLResponse * response,
                                               NSData * data,
                                               NSError * error) {
                               if (!error){
                                   UIImage *image = [UIImage imageWithData:data];
                                   self.awayTeamImage.image= image;
                               }
                           }];
    NSURL *url1 = [NSURL URLWithString:[matchDetails valueForKey:@"HomeFlagLogo"]];
    NSURLRequest* request1 = [NSURLRequest requestWithURL:url1];
    [NSURLConnection sendAsynchronousRequest:request1
                                       queue:[NSOperationQueue mainQueue]
                           completionHandler:^(NSURLResponse * response,
                                               NSData * data,
                                               NSError * error) {
                               if (!error){
                                   UIImage *image = [UIImage imageWithData:data];
                                   self.homeTeamImage.image= image;
                               }
                           }];
    
    [self.venueBtn setTitle:[NSString stringWithFormat:@"   %@",[matchDetails valueForKey:@"venue_name"]] forState:UIControlStateNormal];
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

- (IBAction)backBtnClick:(id)sender {
    [self.navigationController popViewControllerAnimated:TRUE];
}

- (IBAction)venueBtnClick:(id)sender {
    UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
    VenueDetailVC *myVC = (VenueDetailVC *)[storyboard instantiateViewControllerWithIdentifier:@"VenueDetailVC"];
    myVC.matchDetails = matchDetails;
    [self.navigationController pushViewController:myVC animated:YES];

}
@end
