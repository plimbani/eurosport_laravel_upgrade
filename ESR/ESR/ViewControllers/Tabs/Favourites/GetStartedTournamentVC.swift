//
//  GetStartedTournamentVC.swift
//  ESR
//
//  Created by Pratik Patel on 20/03/19.
//

import UIKit

class GetStartedTournamentVC: SuperViewController {

    @IBOutlet var txtTournamentCode: UITextField!
    @IBOutlet var btnSubmit: UIButton!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        btnSubmit.isEnabled = false
        btnSubmit.backgroundColor = UIColor.btnDisable
        
        txtTournamentCode.setLeftPaddingPoints(5)
        txtTournamentCode.addTarget(self, action: #selector(textFieldDidChange(textField:)), for: .editingChanged)
        
        ApplicationData.setBorder(view: txtTournamentCode, Color: .gray, CornerRadius: 1.0, Thickness: 1.0)
        
        hideKeyboardWhenTappedAround()
    }
    
    @IBAction func btnSubmitPressed(_ sender: UIButton) {
        accessCodeAPI()
    }
    
    @objc func textFieldDidChange(textField: UITextField){
        updateUpdateBtn()
    }
    
    func updateUpdateBtn(){
        btnSubmit.isEnabled = false
        btnSubmit.backgroundColor = UIColor.btnDisable
        
        if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
            btnSubmit.setBackgroundImage(nil, for: .normal)
        }
        
        if let text = txtTournamentCode.text {
            if text.trimmingCharacters(in: .whitespacesAndNewlines).isEmpty {
                return
            }
        }
        
        btnSubmit.isEnabled = true
        btnSubmit.backgroundColor = UIColor.btnYellow
        
        if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
            btnSubmit.setBackgroundImage(UIImage.init(named: "btn_yellow"), for: .normal)
        }
    }
    
    func accessCodeAPI() {
        if APPDELEGATE.reachability.connection == .none {
            self.view.hideProgressHUD()
            return
        }
        
        var parameters: [String: Any] = [:]
        parameters["accessCode"] = txtTournamentCode.text!
        self.view.showProgressHUD()
        ApiManager().accessCode(parameters, success: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let dicTournament = result.value(forKey: "data") as? NSDictionary {
                    let tournament = ParseManager.parseTournament(dicTournament)
                    ApplicationData.sharedInstance().saveSelectedTournament(tournament)
                    UIApplication.shared.keyWindow?.rootViewController = Storyboards.Main.instantiateMainVC()
                }
            }
        }, failure: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let message = result.value(forKey: "message") as? String {
                    self.showCustomAlertVC(title: String.localize(key: "alert_title_error"), message: message)
                }
            }
        })
    }
}




