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
    
    // PickerHandlerView
    var pickerHandlerView: PickerHandlerView!
    var titleList = [String]()
    
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
        
        pickerHandlerView = getPickerView()
        pickerHandlerView.delegate = self
        self.view.addSubview(pickerHandlerView)
        
        // Events when keyboard shows and hides
        NotificationCenter.default.addObserver(self, selector: #selector(keyboardWillShow), name: NSNotification.Name.UIKeyboardWillShow, object: nil)
        // To show/hide internet view in Navigation bar
        NotificationCenter.default.addObserver(self, selector: #selector(showHideNoInternetView(_:)), name: .internetConnectivity, object: nil)
        
        // Hides keyboard if tap outside of view
        hideKeyboardWhenTappedAround()
        
        // Get tournaments API request
        sendRequestForTournaments()
    }
    
    deinit {
        NotificationCenter.default.removeObserver(self, name: .internetConnectivity, object: nil)
    }
    
    //MARK:- Request Methods
    func sendRequestForTournaments() {
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
}

extension CreateAccountVC : PickerHandlerViewDelegate {
    
    func pickerCancelBtnPressed() {}
    
    func pickerDoneBtnPressed(_ title: String) {
        lblTournament.text = title
        lblTournament.textColor = .black
        updateCreateAccountBtn()
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
