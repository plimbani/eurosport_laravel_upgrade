//
//  AppExtensions.swift
//  ESR
//
//  Created by Pratik Patel on 01/02/19.
//

import UIKit

extension UIImageView {
    func setImageColor(color: UIColor) {
        let templateImage = self.image?.withRenderingMode(UIImageRenderingMode.alwaysTemplate)
        self.image = templateImage
        self.tintColor = color
    }
}

extension UIButton {
    func setImageColor(color: UIColor, image: UIImage, state: UIControl.State) {
        let templateImage = image.withRenderingMode(UIImageRenderingMode.alwaysTemplate)
        self.setImage(templateImage, for: state)
        self.tintColor = color
    }
}

extension UIApplication {
    
    var visibleViewController: UIViewController? {
        
        guard let rootViewController = keyWindow?.rootViewController else {
            return nil
        }
        
        return getVisibleViewController(rootViewController)
    }
    
    private func getVisibleViewController(_ rootViewController: UIViewController) -> UIViewController? {
        
        if let presentedViewController = rootViewController.presentedViewController {
            return getVisibleViewController(presentedViewController)
        }
        
        if let navigationController = rootViewController as? UINavigationController {
            return navigationController.visibleViewController
        }
        
        if let tabBarController = rootViewController as? UITabBarController {
            return tabBarController.selectedViewController
        }
        
        return rootViewController
    }
}
