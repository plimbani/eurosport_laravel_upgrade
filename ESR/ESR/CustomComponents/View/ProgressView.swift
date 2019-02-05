//
//  ProgressView.swift
//  Marketti
//
//  Created by My on 12/03/18.
//  Copyright © 2018 My. All rights reserved.
//

import UIKit
import MBProgressHUD

open class ProgressView {
    
    var containerView = UIView()
    var progressView = UIView()
    var activityIndicator = UIActivityIndicatorView()
    
    open class var shared: ProgressView {
        struct Static {
            static let instance: ProgressView = ProgressView()
        }
        return Static.instance
    }
    
    open func showProgressView(_ view: UIView) {
        let loadingNotification = MBProgressHUD.showAdded(to: view, animated: true)
        loadingNotification.mode = MBProgressHUDMode.indeterminate
    }
    
    open func hideProgressView(_ view: UIView) {
        MBProgressHUD.hide(for: view, animated: true)
    }
}
