//
//  CreateAccountVC.swift
//  ESR
//
//  Created by Pratik Patel on 13/08/18.
//

import UIKit

class CreateAccountVC: SuperViewController {

    @IBOutlet var table: UITableView!
    @IBOutlet var lblNoInternet: UILabel!
    
    var txtFirstName: UITextField!
    var txtLastName: UITextField!
    var txtEmail: UITextField!
    var txtPassword: UITextField!
    var txtConfirmPassword: UITextField!
    
    var lblTournament: UILabel!
    var btnCreateNewAccount: UIButton!
    
    var fieldList = NSArray()
    var cellList = NSMutableArray()
    
    var heightLabelSelectionCell: CGFloat = 0
    var heightTextFieldCell: CGFloat = 0
    var heightButtonCell: CGFloat = 0
    var heightLabelCell: CGFloat = 0
    var heightTextViewCell: CGFloat = 0
    
    // PickerHandlerView
    var pickerHandlerView: PickerHandlerView!
    var titleList = [String]()
    var tournamentList = NSArray()
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
    }
    
    func initialize(){
        titleNavigationBar.delegate = self
        
        // Checks internet connectivity
        setConstraintLblNoInternet(APPDELEGATE.reachability.connection == .none)
        
        // Fieldlist
        if let url = Bundle.main.url(forResource: "CreateAccountFieldList", withExtension: "plist") {
            fieldList = NSArray(contentsOf: url)!
        }
        
        // Heights for cell
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.LabelSelectionCell)
        heightLabelSelectionCell = (cellOwner.cell as! LabelSelectionCell).getCellHeight()
        
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.TextFieldCell)
        heightTextFieldCell = (cellOwner.cell as! TextFieldCell).getCellHeight()
        
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.ButtonCell)
        heightButtonCell = (cellOwner.cell as! ButtonCell).getCellHeight()
        
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.LabelCell)
        heightLabelCell = (cellOwner.cell as! LabelCell).getCellHeight()
        
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.TextViewCell)
        heightTextViewCell = (cellOwner.cell as! TextViewCell).getCellHeight()
        
        pickerHandlerView = getPickerView()
        pickerHandlerView.delegate = self
        self.view.addSubview(pickerHandlerView)
        
        // Events when keyboard shows and hides
        NotificationCenter.default.addObserver(self, selector: #selector(keyboardWillShow), name: NSNotification.Name.UIKeyboardWillShow, object: nil)
        // To show/hide internet view in Navigation bar
        NotificationCenter.default.addObserver(self, selector: #selector(showHideNoInternetView(_:)), name: .internetConnectivity, object: nil)
        
        // AlertView
        initInfoAlertView(self.view, self)
        
        // Hides keyboard if tap outside of view
        hideKeyboardWhenTappedAround()
        
        // Get tournaments API request
        sendGetTournamentsRequest()
    }
    
    deinit {
        NotificationCenter.default.removeObserver(self, name: .internetConnectivity, object: nil)
    }
    
    func sendRegisterRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        
        var parameters: [String: Any] = [:]
        parameters["email"] = txtEmail.text!
        parameters["password"] = txtPassword.text!
        parameters["first_name"] = txtFirstName.text!
        parameters["sur_name"] = txtLastName.text!
        
        if self.tournamentList.count > 0 {
            parameters["tournament_id"] = (self.tournamentList[pickerHandlerView.selectedPickerPosition] as! NSDictionary).value(forKey: "id")
        }
        
        ApiManager().register(parameters, success: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                self.showInfoAlertView(title: String.localize(key: "alert_title_success"), message: String.localize(key: "alert_create_account_email"))
            }
        }, failure: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if result.allKeys.count == 0 {
                    return
                }
                
                if let error = result.value(forKey: "error") as? String {
                    self.showInfoAlertView(title: String.localize(key: "alert_title_error"), message: error)
                }
            }
        })
    }
    
    //MARK:- Request Methods
    func sendGetTournamentsRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        ApiManager().getTournaments(success: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let tournamentList = result.value(forKey: "data") as? NSArray {
                    for tournament in tournamentList {
                        self.titleList.append((tournament as! NSDictionary).value(forKey: "name") as! String)
                        self.tournamentList.adding(tournament)
                    }
                }
                
                self.pickerHandlerView.titleList = self.titleList
                self.pickerHandlerView.reloadPickerView()
            }
            
        }, failure: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
            }
        })
    }
    
    @objc func showHideNoInternetView(_ notification: NSNotification) {
        if notification.userInfo != nil {
            if let isShow = notification.userInfo![kNotification.isShow] as? Bool {
                setConstraintLblNoInternet(isShow)
            }
        }
    }
    
    @objc func keyboardWillShow(notification: NSNotification) {
        if let newFrame = (notification.userInfo?[ UIKeyboardFrameEndUserInfoKey ] as? NSValue)?.cgRectValue {
            let insets = UIEdgeInsetsMake( 0, 0, newFrame.height, 0 )
            table.contentInset = insets
            table.scrollIndicatorInsets = insets
        }
    }
    
    @objc func termsNPolicyPressed(gestureRecognizer: UITapGestureRecognizer) {
        if let txtViewTermsNPrivacy = gestureRecognizer.view as? UITextView {
            let location: CGPoint = gestureRecognizer.location(in: txtViewTermsNPrivacy)
            
            let tapPosition: UITextPosition = txtViewTermsNPrivacy.closestPosition(to: location)!
            let textRange: UITextRange? = txtViewTermsNPrivacy.tokenizer.rangeEnclosingPosition(tapPosition, with: UITextGranularity.word, inDirection: UITextLayoutDirection.right.rawValue)
            
            if textRange != nil {
                let textClicked = txtViewTermsNPrivacy.text(in: textRange!)
                if textClicked != nil {
                    if "Terms of Use".contains(textClicked!) {
                        self.navigationController?.pushViewController(Storyboards.Settings.instantiatePrivacyAndTermsVC(), animated: true)
                    }
                }
            }
        }
    }
    
    func updateCreateAccountBtn() {
        btnCreateNewAccount.isEnabled = false
        btnCreateNewAccount.backgroundColor = UIColor.btnDisable
        
        if let text = txtFirstName.text {
            if text.trimmingCharacters(in: .whitespacesAndNewlines).isEmpty {
                return
            }
        }
        
        if let text = txtLastName.text {
            if text.trimmingCharacters(in: .whitespacesAndNewlines).isEmpty {
                return
            }
        }
        
        if let text = txtEmail.text {
            if text.trimmingCharacters(in: .whitespacesAndNewlines).isEmpty {
                return
            }
            
            if !Utils.isValidEmail(text) {
                return
            }
        }
        
        if let text = txtPassword.text {
            if text.trimmingCharacters(in: .whitespacesAndNewlines).isEmpty {
                return
            }
        }
        
        if let text = txtConfirmPassword.text {
            if text.trimmingCharacters(in: .whitespacesAndNewlines).isEmpty {
                return
            }
        }
        
        if let text = lblTournament.text {
            if text.trimmingCharacters(in: .whitespaces).isEmpty {
                return
            }
        }
        
        btnCreateNewAccount.isEnabled = true
        btnCreateNewAccount.backgroundColor = UIColor.btnYellow
    }
    
    func addUnderLineToAttributedString(_ attrString: NSMutableAttributedString, _ mainString: String, _ subString: String,_ underlineColor: UIColor,_ foregroundColor: UIColor) {
        
        let nsRangeTerms = NSString(string: mainString).range(of: subString, options: String.CompareOptions.caseInsensitive)
        attrString.addAttribute(NSAttributedStringKey.underlineStyle, value: NSUnderlineStyle.styleSingle.rawValue, range: nsRangeTerms)
        attrString.addAttribute(NSAttributedStringKey.foregroundColor, value: foregroundColor, range: nsRangeTerms)
        attrString.addAttribute(NSAttributedStringKey.underlineColor, value: underlineColor, range: nsRangeTerms)
    }
}

extension CreateAccountVC: UIGestureRecognizerDelegate {
    func gestureRecognizer(_ gestureRecognizer: UIGestureRecognizer, shouldRecognizeSimultaneouslyWith otherGestureRecognizer: UIGestureRecognizer) -> Bool {
        return true
    }
}

extension CreateAccountVC: CustomAlertViewDelegate {
    func customAlertViewOkBtnPressed(requestCode: Int) {
        self.navigationController?.popViewController(animated: true)
    }
}

extension CreateAccountVC: PickerHandlerViewDelegate {
    
    func pickerCancelBtnPressed() {}
    
    func pickerDoneBtnPressed(_ title: String) {
        lblTournament.text = title
        lblTournament.textColor = .black
        updateCreateAccountBtn()
    }
}

extension CreateAccountVC: UITextViewDelegate {
    func textViewShouldBeginEditing(_ textView: UITextView) -> Bool {
        return false
    }
}

extension CreateAccountVC : TitleNavigationBarDelegate {
    func titleNavBarBackBtnPressed() {
        self.navigationController?.popViewController(animated: true)
    }
}

extension CreateAccountVC : UITextFieldDelegate {
    
    func textFieldShouldReturn(_ textField: UITextField) -> Bool {
        if textField == txtFirstName {
            txtLastName.becomeFirstResponder()
        } else if textField == txtLastName {
            txtEmail.becomeFirstResponder()
        } else if textField == txtEmail {
            txtPassword.becomeFirstResponder()
        } else if textField == txtPassword {
            txtConfirmPassword.becomeFirstResponder()
        }
        return true
    }
    
    @objc func textFieldDidChange(textField: UITextField){
        updateCreateAccountBtn()
    }
}

extension CreateAccountVC : UITableViewDataSource, UITableViewDelegate {
    
    func getCellHeight(_ indexPath: IndexPath) -> CGFloat {
        var height:CGFloat = 0
        let field = fieldList[indexPath.row] as! NSDictionary
        if let rawValue = field.value(forKey: "cellType") as? Int {
            if let cellType = CellType(rawValue: rawValue) {
                switch cellType {
                    case .LabelSelectionCell:
                        height = heightLabelSelectionCell
                    case .TextFieldCell:
                        height = heightTextFieldCell
                    case .ButtonCell:
                        height = heightButtonCell
                    case .TextViewCell:
                        height = heightTextViewCell
                    default:
                        print("Default")
                }
            }
        }
        return height
    }
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return fieldList.count
    }
    
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return getCellHeight(indexPath)
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        
        if cellList.count > indexPath.row {
            return cellList[indexPath.row] as! UITableViewCell
        } else {
            let field = fieldList[indexPath.row] as! NSDictionary
            print(field)
            if let rawValue = field.value(forKey: "cellType") as? Int {
                var cell:UITableViewCell! = nil
                if let cellType = CellType(rawValue: rawValue) {
                    switch(cellType) {
                        case .TextFieldCell:
                            _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.TextFieldCell)
                            let textFieldCell = cellOwner.cell as! TextFieldCell
                            textFieldCell.record = field
                            textFieldCell.reloadCell()
                            cell = textFieldCell
                            cellList.add(cell)
                            textFieldCell.txtField.addTarget(self, action: #selector(textFieldDidChange(textField:)), for: .editingChanged)
                            textFieldCell.txtField.delegate = self
                            if indexPath.row == 0 {
                                txtFirstName = textFieldCell.txtField
                            } else if indexPath.row == 1 {
                                txtLastName = textFieldCell.txtField
                            } else if indexPath.row == 2 {
                                txtEmail = textFieldCell.txtField
                            } else if indexPath.row == 3 {
                                txtPassword = textFieldCell.txtField
                            } else if indexPath.row == 4 {
                                txtConfirmPassword = textFieldCell.txtField
                            }
                        case .LabelSelectionCell:
                            _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.LabelSelectionCell)
                            let labelSelectionCell = cellOwner.cell as! LabelSelectionCell
                            labelSelectionCell.record = field
                            labelSelectionCell.reloadCell()
                            lblTournament = labelSelectionCell.lblTitle
                            cell = labelSelectionCell
                            cellList.add(cell)
                        case .ButtonCell:
                            _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.ButtonCell)
                            let buttonCell = cellOwner.cell as! ButtonCell
                            buttonCell.record = field
                            buttonCell.reloadCell()
                            btnCreateNewAccount = buttonCell.btn
                            btnCreateNewAccount.isEnabled = true
                            cell = buttonCell
                            cellList.add(cell)
                        case .TextViewCell:
                            _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.TextViewCell)
                            let textViewCell = cellOwner.cell as! TextViewCell
                            textViewCell.textView.text = ""
                            cell = textViewCell
                            cellList.add(cell)
                        
                            // Attributed string
                            let mainString = String.localize(key: "string_accept_terms_of_use")
                            let attrString = NSMutableAttributedString.init(string: mainString)
                            let nsRange = NSString(string: mainString).range(of: mainString, options: String.CompareOptions.caseInsensitive)
                        
                            attrString.addAttribute(NSAttributedStringKey.foregroundColor, value: UIColor.black, range: nsRange)
                            attrString.addAttribute(NSAttributedStringKey.font, value: UIFont(name: Font.HELVETICA_REGULAR, size: 15.0)!, range: NSRange.init(location: 0, length: mainString.count))
                        
                            addUnderLineToAttributedString(attrString, mainString, "Terms of Use", UIColor.btnYellow, UIColor.btnYellow)
                        
                            let tapGesture = UITapGestureRecognizer(target: self, action: #selector(termsNPolicyPressed))
                            tapGesture.delegate = self
                            textViewCell.textView.attributedText = attrString
                            textViewCell.textView.delegate = self
                            textViewCell.textView.textAlignment = .left
                            textViewCell.textView.addGestureRecognizer(tapGesture)
                        default:
                            print("Default")
                    }
                }
                cell.backgroundColor = .clear
                return cell
            }
        }
        return UITableViewCell()
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        let field = fieldList[indexPath.row] as! NSDictionary
        if let rawValue = field.value(forKey: "cellType") as? Int {
            if let cellType = CellType(rawValue: rawValue) {
                if cellType == .LabelSelectionCell {
                    self.view.endEditing(true)
                    pickerHandlerView.show()
                } else if cellType == .ButtonCell {
                    
                }
            }
        }
    }
}
