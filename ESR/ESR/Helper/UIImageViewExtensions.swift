//
//  UIImageViewExtensions.swift
//  ESR
//
//  Created by Pratik Patel on 04/09/18.
//

import UIKit

extension UIImageView {
    func setImageColor(color: UIColor) {
        let templateImage = self.image?.withRenderingMode(UIImageRenderingMode.alwaysTemplate)
        self.image = templateImage
        self.tintColor = color
    }
}
