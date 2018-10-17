//
//  ApplicationUpdateVC.h
//  ESR
//
//  Created by Aecor Digital on 09/01/18.
//  Copyright © 2018 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ApplicationUpdateVC : UIViewController

@property (nonatomic,retain) NSString *isFirsttime;

- (IBAction)updateBtnClick:(id)sender;
- (IBAction)cancelBtnClick:(id)sender;


@end
