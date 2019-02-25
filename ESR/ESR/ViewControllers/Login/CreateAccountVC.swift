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
    @IBOutlet var btnBack: UIButton!
    @IBOutlet var logoImg: UIImageView!
    
    var txtFirstName: UITextField!
    var txtLastName: UITextField!
    var txtEmail: UITextField!
    var txtPassword: UITextField!
    var txtConfirmPassword: UITextField!
    
    var lblTournament: UILabel!
    var lblRole: UILabel!
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
    var tournamentTitleList = [String]()
    var tournamentList = NSArray()
    
    var paramTournamentId = -1
    
    var isRole = false
    var selectedRole = NULL_STRING
    
    enum CreateAccountList: Int {
        case firstname = 0
        case surname = 1
        case role = 2
        case email = 3
        case pass = 4
        case confirmPass = 5
        case selectTournament = 6
        case createAccount = 8
    }
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
        let tap = UITapGestureRecognizer(target: self, action: #selector(onLogoImageClick(_:)))
        logoImg.isUserInteractionEnabled = true
        logoImg.addGestureRecognizer(tap)
    }
    
    func initialize(){
        
        if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
            btnBack.setImageColor(color: UIColor.AppColor(), image: UIImage.init(named: "back_white")!, state: .normal)
        }
        
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
        // NotificationCenter.default.addObserver(self, selector: #selector(keyboardWillShow), name: NSNotification.Name.UIKeyboardWillShow, object: nil)
        // To show/hide internet view in Navigation bar
        NotificationCenter.default.addObserver(self, selector: #selector(showHideNoInternetView(_:)), name: .internetConnectivity, object: nil)
        
        // AlertView
        // initInfoAlertView(self.view, self)
        
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
        parameters["role"] = selectedRole
        
        if self.tournamentList.count > 0 {
            parameters["tournament_id"] = (self.tournamentList[pickerHandlerView.selectedPickerPosition] as! NSDictionary).value(forKey: "id")
        }
        
        ApiManager().register(parameters, success: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                // self.showInfoAlertView(title: String.localize(key: "alert_title_success"), message: String.localize(key: "alert_create_account_email"))
                
                self.showCustomAlertVC(title: String.localize(key: "alert_title_success"), message: String.localize(key: "alert_create_account_email"), delegate: self)
            }
        }, failure: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if result.allKeys.count == 0 {
                    return
                }
                
                if let error = result.value(forKey: "error") as? String {
                    // self.showInfoAlertView(title: String.localize(key: "alert_title_error"), message: error)
                    self.showCustomAlertVC(title: String.localize(key: "alert_title_error"), message: error)
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
                        self.tournamentTitleList.append((tournament as! NSDictionary).value(forKey: "name") as! String)
                    }
                    
                    self.tournamentList = tournamentList
                }
                
                self.pickerHandlerView.titleList = self.tournamentTitleList
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
    
    @objc func onLogoImageClick(_ sender : UITapGestureRecognizer) {
        self.navigationController?.popToRootViewController(animated: true)
    }
    func updateCreateAccountBtn() {
        btnCreateNewAccount.isEnabled = false
        btnCreateNewAccount.backgroundColor = UIColor.btnDisable
        
        if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
            btnCreateNewAccount.setBackgroundImage(nil, for: .normal)
        }
        
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
        
        if paramTournamentId == -1 {
            return
        }
        
        btnCreateNewAccount.isEnabled = true
        btnCreateNewAccount.backgroundColor = UIColor.btnYellow
        
        if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
            btnCreateNewAccount.setBackgroundImage(UIImage.init(named: "btn_yellow"), for: .normal)
        }
    }
    
    
    @IBAction func btnBackPressed(_ sender: UIButton) {
            self.navigationController?.popViewController(animated: true)
    }
    
    @objc func btnCreateAccountPressed(_ btn: UIButton) {
        sendRegisterRequest()
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

extension CreateAccountVC: CustomAlertVCDelegate {
    func customAlertVCOkBtnPressed(requestCode: Int) {
        self.navigationController?.popViewController(animated: true)
    }
}

extension CreateAccountVC: PickerHandlerViewDelegate {
    
    func pickerCancelBtnPressed() {}
    
    func pickerDoneBtnPressed(_ title: String) {
        
        if isRole {
            isRole = false
            lblRole.text = title
            lblRole.textColor = .black
            selectedRole = title
        } else {
            lblTournament.text = title
            paramTournamentId = (self.tournamentList[pickerHandlerView.selectedPickerPosition] as! NSDictionary).value(forKey: "id") as! Int
            lblTournament.textColor = .black
        }
        
        updateCreateAccountBtn()
    }
}

extension CreateAccountVC: UITextViewDelegate {
    func textViewShouldBeginEditing(_ textView: UITextView) -> Bool {
        return false
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
                            
                            if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
                                ApplicationData.setBorder(view: textFieldCell.txtField, Color: .gray, CornerRadius: 0.0, Thickness: 1.0)
                            }
                            
                            if indexPath.row == CreateAccountList.firstname.rawValue {
                                txtFirstName = textFieldCell.txtField
                                
                                txtFirstName.placeholder = String.localize(key: "First name")
                            } else if indexPath.row == CreateAccountList.surname.rawValue {
                                txtLastName = textFieldCell.txtField
                                txtLastName.placeholder = String.localize(key: "Surname")
                            } else if indexPath.row == CreateAccountList.email.rawValue {
                                txtEmail = textFieldCell.txtField
                                txtEmail.autocapitalizationType = .none
                                txtEmail.placeholder = String.localize(key: "Email address")
                            } else if indexPath.row == CreateAccountList.pass.rawValue {
                                txtPassword = textFieldCell.txtField
                                txtPassword.placeholder = String.localize(key: "Password")
                            } else if indexPath.row == CreateAccountList.confirmPass.rawValue {
                                txtConfirmPassword = textFieldCell.txtField
                                txtConfirmPassword.placeholder = String.localize(key: "Confirm password")
                            }
                        case .LabelSelectionCell:
                            _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.LabelSelectionCell)
                            let labelSelectionCell = cellOwner.cell as! LabelSelectionCell
                            labelSelectionCell.record = field
                            labelSelectionCell.reloadCell()
                            
                            if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
                                ApplicationData.setBorder(view: labelSelectionCell.containerView, Color: .gray, CornerRadius: 0.0, Thickness: 1.0)
                            }
                            
                            if indexPath.row == CreateAccountList.selectTournament.rawValue {
                                lblTournament = labelSelectionCell.lblTitle
                            } else if indexPath.row == CreateAccountList.role.rawValue {
                                lblRole = labelSelectionCell.lblTitle
                            }
                            
                            cell = labelSelectionCell
                            cellList.add(cell)
                        case .ButtonCell:
                            _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.ButtonCell)
                            let buttonCell = cellOwner.cell as! ButtonCell
                            buttonCell.record = field
                            buttonCell.reloadCell()
                            buttonCell.btn.addTarget(self, action: #selector(btnCreateAccountPressed), for: .touchUpInside)
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
                        
                            if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
                                attrString.addAttribute(NSAttributedStringKey.foregroundColor, value: UIColor.AppColor(), range: nsRange)
                            } else {
                                attrString.addAttribute(NSAttributedStringKey.foregroundColor, value: UIColor.white, range: nsRange)
                            }
                            
                            attrString.addAttribute(NSAttributedStringKey.font, value: UIFont(name: Font.HELVETICA_REGULAR, size: 13.0)!, range: NSRange.init(location: 0, length: mainString.count))
                        
                            addUnderLineToAttributedString(attrString, mainString, "Terms of Use.", UIColor.btnYellow, UIColor.btnYellow)
                        
                            let tapGesture = UITapGestureRecognizer(target: self, action: #selector(termsNPolicyPressed))
                            tapGesture.delegate = self
                            textViewCell.textView.attributedText = attrString
                            textViewCell.textView.delegate = self
                            textViewCell.textView.textAlignment = .center
                            textViewCell.textView.contentInset = UIEdgeInsetsMake(-6.0,0.0,0,0.0);
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
        self.view.endEditing(true)
                   
        if indexPath.row == CreateAccountList.selectTournament.rawValue {
            pickerHandlerView.titleList = tournamentTitleList
            
            pickerHandlerView.reloadPickerView()
            pickerHandlerView.show()
        } else if indexPath.row == CreateAccountList.role.rawValue {
            pickerHandlerView.titleList = ApplicationData.rolesList
            isRole = true
            
            pickerHandlerView.reloadPickerView()
            pickerHandlerView.show()
        }
        
        
    }
}
