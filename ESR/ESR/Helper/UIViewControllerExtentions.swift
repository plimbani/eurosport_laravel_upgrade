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
    
    func getPickerView(_ titleList:[String] = []) -> PickerHandlerView {
        let cellOwner = TableCellOwner()
        _ = cellOwner.loadMyNibFile(nibName: "PickerHandlerView")
        let picker = cellOwner.view as! PickerHandlerView
        picker.frame = CGRect(x: 0, y: 0, width: DEVICE_WIDTH, height: DEVICE_HEIGHT)
        picker.titleList = titleList
        picker.hide()
        return picker
    }
}
