//
//  CreateAccountVC.swift
//  ESR
//
//  Created by Pratik Patel on 13/08/18.
//

import UIKit

class CreateAccountVC: SuperViewController {

    @IBOutlet var table: UITableView!
    @IBOutlet var titleNavigationBar: TitleNavigationBar!
    
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
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
    }
    
    func initialize(){
        
        // Fieldlist
        if let url = Bundle.main.url(forResource: "CreateAccountFieldList", withExtension: "plist") {
            fieldList = NSArray(contentsOf: url)!
        }
        
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.LabelSelectionCell)
        heightLabelSelectionCell = (cellOwner.cell as! LabelSelectionCell).getCellHeight()
        
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.TextFieldCell)
        heightTextFieldCell = (cellOwner.cell as! TextFieldCell).getCellHeight()
        
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.ButtonCell)
        heightButtonCell = (cellOwner.cell as! ButtonCell).getCellHeight()
        
        // Hides keyboard if tap outside of view
        hideKeyboardWhenTappedAround()
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
        
        btnCreateNewAccount.isEnabled = true
        btnCreateNewAccount.backgroundColor = UIColor.btnYellow
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
                    default:
                        print("default")
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
                        default:
                            print("")
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
                } else if cellType == .ButtonCell {
                    
                }
            }
        }
    }
}
