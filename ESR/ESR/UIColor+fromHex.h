//
//  UIColor+fromHex.h
//  VehicleCheck
//
//  Created by Aecor Digital on 10/02/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface UIColor (fromHex)
+ (UIColor *)colorwithHexString:(NSString *)hexStr alpha:(CGFloat)alpha;
@end
