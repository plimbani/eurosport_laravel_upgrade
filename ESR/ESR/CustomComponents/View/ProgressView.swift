//
//  ProgressView.swift
//  Marketti
//
//  Created by My on 12/03/18.
//  Copyright © 2018 My. All rights reserved.
//

import UIKit

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
    containerView.frame = view.frame
    containerView.center = view.center
    containerView.backgroundColor = UIColor(hex: 0xffffff, Alpha: 0.3)
    
    progressView.frame = CGRect(x: 0, y: 0, width: 80, height: 80)
    progressView.center = view.center
    progressView.backgroundColor = UIColor(hex: 0x444444, Alpha: 0.7)
    progressView.clipsToBounds = true
    progressView.layer.cornerRadius = 10
    
    activityIndicator.frame = CGRect(x: 0, y: 0, width: 40, height: 40)
    activityIndicator.activityIndicatorViewStyle = .whiteLarge
    activityIndicator.center = CGPoint(x: progressView.bounds.width / 2, y: progressView.bounds.height / 2)
    
    progressView.addSubview(activityIndicator)
    containerView.addSubview(progressView)
    view.addSubview(containerView)
    
    activityIndicator.startAnimating()
  }
  
  open func hideProgressView() {
    activityIndicator.stopAnimating()
    containerView.removeFromSuperview()
  }
}
