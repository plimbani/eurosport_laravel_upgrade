//
//  WebVC.m
//  ESR
//
//  Created by Aecor Digital on 11/08/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "WebVC.h"

@interface WebVC ()

@end

@implementation WebVC
@synthesize socialName;

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view.
    NSString *fullURL;;
    
    if ([socialName isEqualToString:@"facebook"]) {
        self.titleLbl.text = @"FACEBOOK";
        fullURL = @"https://www.facebook.com/eurosportring/";
    }else if([socialName isEqualToString:@"twitter"]){
        self.titleLbl.text = @"TWITTER";
        fullURL = @"https://twitter.com/EuroSportring";
    }else if([socialName isEqualToString:@"instagram"]){
        self.titleLbl.text = @"INSTAGRAM";
        fullURL =@"http://instagram.com/_u/eurosports";
    }
    NSURL *url = [NSURL URLWithString:fullURL];
    NSURLRequest *requestObj = [NSURLRequest requestWithURL:url];
    [self.webView loadRequest:requestObj];
    UITapGestureRecognizer *tapAction = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(lblClick:)];
    tapAction.delegate =self;
    tapAction.numberOfTapsRequired = 1;
    
    //Enable the lable UserIntraction
    _titleLbl.userInteractionEnabled = YES;
    [_titleLbl addGestureRecognizer:tapAction];
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
@end
