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
