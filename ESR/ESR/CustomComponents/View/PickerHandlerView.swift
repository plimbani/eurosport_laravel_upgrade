//
//  PickerHandlerView.swift
//  Marketti
//
//  Created by My on 04/04/18.
//  Copyright © 2018 Aecor. All rights reserved.
//

import UIKit

protocol PickerHandlerViewDelegate {
    func pickerCancelBtnPressed()
    func pickerDoneBtnPressed(_ title:String)
}

class PickerHandlerView: UIView {
    
    @IBOutlet var btnCancel: UIBarButtonItem!
    @IBOutlet var btnDone: UIBarButtonItem!
    @IBOutlet var picker_view: UIPickerView!
    @IBOutlet var mainView: UIView!
    
    var delegate: PickerHandlerViewDelegate?
    var titleList = [String]()
    var selectedTitle = NULL_STRING
    var selectedPickerPosition = NULL_ID
    
    override func awakeFromNib() {
        btnDone.setTitleTextAttributes([NSAttributedStringKey(rawValue: NSAttributedStringKey.font.rawValue): UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonBtnSize)], for: .normal)
        btnCancel.setTitleTextAttributes([NSAttributedStringKey(rawValue: NSAttributedStringKey.font.rawValue): UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonBtnSize)], for: .normal)
        
        if titleList.count > 0 {
            picker_view.selectRow(0, inComponent: 0, animated: false)
        }
        
        let tap = UITapGestureRecognizer(target: self, action: #selector(handleTap(_:)))
        mainView.addGestureRecognizer(tap)
    }
    
    @objc func handleTap(_ sender: UITapGestureRecognizer? = nil) {
        hide()
        delegate?.pickerCancelBtnPressed()
    }
    
    func show() {
        UIView.beginAnimations(nil, context: nil)
        UIView.setAnimationDuration(0.35)
        self.alpha = 1.0
        UIView.commitAnimations()
    }
    
    func hide(flag isAnimate: Bool = false) {
        UIView.animate(withDuration: isAnimate ? 0.5 : 0, delay: 0.1, options: .curveEaseOut, animations: {
            UIView.beginAnimations(nil, context: nil)
            UIView.setAnimationDuration(isAnimate ? 0.35 : 0)
            self.alpha = 0.0
            UIView.commitAnimations()
        }, completion: { (finished: Bool) in
        })
    }
    
    func reloadPickerView() {
        if titleList.count > 0 {
            selectedTitle = titleList[0]
            selectedPickerPosition = 0
        }
        picker_view.reloadAllComponents()
        if titleList.count > 0 {
            picker_view.selectRow(0, inComponent: 0, animated: false)
        }
    }
    
    @IBAction func cancelButtonPressed(_ sender: UIBarButtonItem) {
        delegate?.pickerCancelBtnPressed()
        hide()
    }
    
    @IBAction func doneButtonPressed(_ sender: UIBarButtonItem) {
        if selectedTitle.isEmpty && titleList.count > 0 {
            selectedTitle = titleList[0]
        }
        delegate?.pickerDoneBtnPressed(selectedTitle)
        hide()
    }
}

extension PickerHandlerView: UIPickerViewDataSource, UIPickerViewDelegate {
    func numberOfComponents(in pickerView: UIPickerView) -> Int {
        return 1;
    }
    
    func pickerView(_ pickerView: UIPickerView, numberOfRowsInComponent component: Int) -> Int {
        return titleList.count;
    }
    
    func pickerView(_ pickerView: UIPickerView, viewForRow row: Int, forComponent component: Int, reusing view: UIView?) -> UIView {
        let label = (view as? UILabel) ?? UILabel()
        
        label.textColor = .black
        label.textAlignment = .center
        label.font = UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonBtnSize)
        label.textColor = .white
        label.text = titleList[row]
        return label
    }
    
    func pickerView(_ pickerView: UIPickerView, didSelectRow row: Int, inComponent component: Int) {
        selectedTitle = titleList[row]
        selectedPickerPosition = row
    }
}
