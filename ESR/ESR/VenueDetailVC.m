//
//  VenueDetailVC.m
//  ESR
//
//  Created by Aecor Digital on 07/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "VenueDetailVC.h"
#import "MapVC.h"
#import "Reachability.h"
#import "Utils.h"

@interface VenueDetailVC ()

@end

@implementation VenueDetailVC
@synthesize matchDetails;

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view.
    if (![[matchDetails valueForKey:@"pitch_number"] isKindOfClass:[NSNull class]]) {
        self.pitchName.text = [matchDetails valueForKey:@"pitch_number"];
    }else{
        self.pitchName.text = @"NA";
    }
    if (![[matchDetails valueForKey:@"venue_name"] isKindOfClass:[NSNull class]]) {
        self.location.text =[matchDetails valueForKey:@"venue_name"];
    }else{
        self.location.text =@"NA";;
    }
    
    
    self.address.text = @"";
    if (![[matchDetails valueForKey:@"venueaddress"] isKindOfClass:[NSNull class]]) {
        if (![[matchDetails valueForKey:@"venueaddress"] isEqualToString:@""]) {
            self.address.text = [NSString stringWithFormat:@"%@",[matchDetails valueForKey:@"venueaddress"]];
        }
    }
    if (![[matchDetails valueForKey:@"venueCity"] isKindOfClass:[NSNull class]]) {
        if (![[matchDetails valueForKey:@"venueCity"] isEqualToString:@""]) {
            self.address.text = [NSString stringWithFormat:@"%@, %@",self.address.text,[matchDetails valueForKey:@"venueCity"]];
        }
    }
    if (![[matchDetails valueForKey:@"venueState"] isKindOfClass:[NSNull class]]) {
        if (![[matchDetails valueForKey:@"venueState"] isEqualToString:@""]) {
            self.address.text = [NSString stringWithFormat:@"%@, %@",self.address.text,[matchDetails valueForKey:@"venueState"]];
        }
    }
    if (![[matchDetails valueForKey:@"venueCountry"] isKindOfClass:[NSNull class]]) {
        if (![[matchDetails valueForKey:@"venueCountry"] isEqualToString:@""]) {
            self.address.text = [NSString stringWithFormat:@"%@, %@",self.address.text,[matchDetails valueForKey:@"venueCountry"]];
        }
    }
    if (![[matchDetails valueForKey:@"venuePostcode"] isKindOfClass:[NSNull class]]) {
        if (![[matchDetails valueForKey:@"venuePostcode"] isEqualToString:@""]) {
            self.address.text = [NSString stringWithFormat:@"%@, %@",self.address.text,[matchDetails valueForKey:@"venuePostcode"]];
        }
    }
    if ([self.address.text isEqualToString:@""]) {
        self.address.text = @"NA";
    }
    
    if (![[matchDetails valueForKey:@"pitchType"] isKindOfClass:[NSNull class]]) {
        self.playingSurface.text = [matchDetails valueForKey:@"pitchType"];
    }else{
        self.playingSurface.text = @"NA";
    }
    if ([[matchDetails valueForKey:@"venueCoordinates"]isKindOfClass:[NSNull class]]) {
        self.bottomMapView.hidden = TRUE;
    }
    UITapGestureRecognizer *tapAction = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(lblClick:)];
    tapAction.delegate =self;
    tapAction.numberOfTapsRequired = 1;
    
    //Enable the lable UserIntraction
    _titleLbl.userInteractionEnabled = YES;
    [_titleLbl addGestureRecognizer:tapAction];
}
- (void)reachabilityChanged:(NSNotification*)notification
{
    Reachability* reachability = notification.object;
    if(reachability.currentReachabilityStatus == NotReachable)
        self.offlineView.hidden = false;
    else
        self.offlineView.hidden = TRUE;
}
-(void)viewWillAppear:(BOOL)animated{
    if([Utils isNetworkAvailable] ==YES){
        self.offlineView.hidden = TRUE;
    }else{
        self.offlineView.hidden = false;
    }
    [[NSNotificationCenter defaultCenter] addObserver: self selector: @selector(reachabilityChanged:) name: kReachabilityChangedNotification object: nil];
    Reachability* reachability = [Reachability reachabilityForInternetConnection];
    [reachability startNotifier];
}
- (void)lblClick:(UITapGestureRecognizer *)tapGesture {
    [self.navigationController popViewControllerAnimated:TRUE];
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

- (IBAction)viewOnMapBtnClick:(id)sender {
    UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
    MapVC *myVC = (MapVC *)[storyboard instantiateViewControllerWithIdentifier:@"MapVC"];
    myVC.matchDetails = matchDetails;
    [self.navigationController pushViewController:myVC animated:YES];
}
@end
