//
//  UIViewExtentions.swift
//  ESR
//
//  Created by Pratik Patel on 17/08/18.
//

import UIKit

extension UIView {
    func showProgressHUD() {
        ProgressView.shared.showProgressView(self)
    }
    
    func hideProgressHUD() {
        ProgressView.shared.hideProgressView(self)
    }
}
