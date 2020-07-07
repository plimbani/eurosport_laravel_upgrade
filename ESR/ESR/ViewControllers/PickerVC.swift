//
//  PickerVC.swift
//  ESR
//
//  Created by Pratik Patel on 05/03/19.
//

import UIKit

protocol PickerVCDelegate {
    func pickerVCDoneBtnPressed(title: String, lastPosition: Int)
    func pickerVCCancelBtnPressed()
}

class PickerVC: UIViewController {

    @IBOutlet var btnCancel: UIBarButtonItem!
    @IBOutlet var btnDone: UIBarButtonItem!
    @IBOutlet var picker_view: UIPickerView!
    @IBOutlet var mainView: UIView!
    
    var delegate: PickerVCDelegate?
    var titleList = [String]()
    var selectedTitle = NULL_STRING
    var selectedPickerPosition = 0
    
    override func viewDidLoad() {
        super.viewDidLoad()
        TestFairy.log(String(describing: self))
        btnDone.setTitleTextAttributes([NSAttributedString.Key(rawValue: NSAttributedString.Key.font.rawValue): UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonBtnSize)], for: .normal)
        btnCancel.setTitleTextAttributes([NSAttributedString.Key(rawValue: NSAttributedString.Key.font.rawValue): UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonBtnSize)], for: .normal)
        
        let tap = UITapGestureRecognizer(target: self, action: #selector(handleTap(_:)))
        mainView.addGestureRecognizer(tap)
        
        if titleList.count > 0 {
            picker_view.reloadAllComponents()
            picker_view.selectRow(selectedPickerPosition, inComponent: 0, animated: false)
        }
    }
    
    @objc func handleTap(_ sender: UITapGestureRecognizer? = nil) {
        TestFairy.log(String(describing: self) + " handleTap")
        self.dismiss(animated: true, completion: nil)
        delegate?.pickerVCCancelBtnPressed()
    }
    
    @IBAction func cancelButtonPressed(_ sender: UIBarButtonItem) {
        TestFairy.log(String(describing: self) + " cancelButtonPressed")
        self.dismiss(animated: true, completion: nil)
        delegate?.pickerVCCancelBtnPressed()
    }
    
    @IBAction func doneButtonPressed(_ sender: UIBarButtonItem) {
        TestFairy.log(String(describing: self) + " doneButtonPressed")
        delegate?.pickerVCDoneBtnPressed(title: titleList[selectedPickerPosition], lastPosition: selectedPickerPosition)
        self.dismiss(animated: true, completion: nil)
    }
}

extension PickerVC: UIPickerViewDataSource, UIPickerViewDelegate {
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
