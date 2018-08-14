//
//  TabBarContentView.swift
//  ESR
//
//  Created by Pratik Patel on 13/08/18.
//

import ESTabBarController_swift

class TabBarContentView: ESTabBarItemContentView {
    public var duration = 0.3
    override init(frame: CGRect) {
        super.init(frame: frame)
        
        // define the text label and icon color for normal and highlighted mode.
        textColor = UIColor.gray
        highlightTextColor = UIColor.AppColor()
        iconColor = UIColor.gray
        highlightIconColor = UIColor.AppColor()
    }
    
    public required init?(coder aDecoder: NSCoder) {
        fatalError("init(coder:) has not been implemented")
    }
    
//    override func selectAnimation(animated: Bool, completion: (() -> ())?) {
//        //self.bounceAnimation()
//        completion?()
//    }
//
//    override func reselectAnimation(animated: Bool, completion: (() -> ())?) {
//        //self.bounceAnimation()
//        completion?()
//    }
//
//    func bounceAnimation() {
//        let impliesAnimation = CAKeyframeAnimation(keyPath: "transform.scale")
//        impliesAnimation.values = [1.0 ,1.4, 0.9, 1.15, 0.95, 1.02, 1.0]
//        impliesAnimation.duration = duration * 2
//        impliesAnimation.calculationMode = kCAAnimationCubic
//        imageView.layer.add(impliesAnimation, forKey: nil)
//    }
}
