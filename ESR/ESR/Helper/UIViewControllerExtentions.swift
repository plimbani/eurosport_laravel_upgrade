//
//  ViewControllerExtentions.swift
//  Marketti
//
//  Created by My on 07/03/18.
//  Copyright © 2018 My. All rights reserved.
//

import UIKit
import Foundation

extension UIViewController {
    
    func hideKeyboardWhenTappedAround() {
        let tap: UITapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(UIViewController.dismissKeyboard))
        tap.cancelsTouchesInView = false
        view.addGestureRecognizer(tap)
    }
    
    @objc func dismissKeyboard() {
        view.endEditing(true)
    }
}
