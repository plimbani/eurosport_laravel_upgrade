//
//  PrivacyAndTermsVC.swift
//  ESR
//
//  Created by Pratik Patel on 21/08/18.
//

import UIKit

class PrivacyAndTermsVC: SuperViewController {

    @IBOutlet var tvHtmlContent: UITextView!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        TestFairy.log(String(describing: self))
        tvHtmlContent.delegate = self
        
        let tapGesture = UITapGestureRecognizer(target: self, action: #selector(termsNPolicyPressed))
        tapGesture.delegate = self
        tvHtmlContent.addGestureRecognizer(tapGesture)
        
        initialize()
    }
    
    func initialize() {
        titleNavigationBar.lblTitle.text = String.localize(key: "title_privacy_terms")
        titleNavigationBar.delegate = self
        titleNavigationBar.setBackgroundColor()
        
        loadHtmlContent()
    }
    
    func loadHtmlContent() {
        if let path = Bundle.main.path(forResource: "privacyContent", ofType: "html") {
            do {
                let htmlString = try String(contentsOfFile:path, encoding: String.Encoding.utf8)
                let htmlData = NSString(string: htmlString).data(using: String.Encoding.utf8.rawValue)
                let options = [NSAttributedString.DocumentReadingOptionKey.documentType: NSAttributedString.DocumentType.html]
                let attributedString = try! NSAttributedString(data: htmlData!, options: options, documentAttributes: nil)
                self.tvHtmlContent.attributedText = attributedString
            } catch _ as NSError {}
        }
    }
    
    @objc func termsNPolicyPressed(gestureRecognizer: UITapGestureRecognizer) {
        if (gestureRecognizer.view as? UITextView) != nil {
            if let txtViewTermsNPrivacy = gestureRecognizer.view as? UITextView {
                let location: CGPoint = gestureRecognizer.location(in: txtViewTermsNPrivacy)
                
                let tapPosition: UITextPosition = txtViewTermsNPrivacy.closestPosition(to: location)!
                let textRange: UITextRange? = txtViewTermsNPrivacy.tokenizer.rangeEnclosingPosition(tapPosition, with: UITextGranularity.word, inDirection: UITextDirection(rawValue: UITextLayoutDirection.right.rawValue))
                
                if textRange != nil {
                    let textClicked = txtViewTermsNPrivacy.text(in: textRange!)
                    if textClicked != nil {
                        
                        let emailStr = "info@euro-sportring.org"
                        
                        if "+31 (0)355489800".contains(textClicked!) {
                            print("Number clicked")
                        } else if emailStr.contains(textClicked!) {
                            openMailApp(emailStr)
                        }
                    }
                }
            }
        }
    }
    
    func openMailApp(_ email: String) {
        if #available(iOS 10.0, *) {
            UIApplication.shared.open(URL(string:"mailto:\(email)")!, options: [:], completionHandler: nil)
        } else {
            UIApplication.shared.openURL(URL(string:"mailto:\(email)")!)
        }
    }
}
    
extension PrivacyAndTermsVC: UIGestureRecognizerDelegate {
    func gestureRecognizer(_ gestureRecognizer: UIGestureRecognizer, shouldRecognizeSimultaneouslyWith otherGestureRecognizer: UIGestureRecognizer) -> Bool {
        return true
    }
}

extension PrivacyAndTermsVC: UITextViewDelegate {
    func textViewShouldBeginEditing(_ textView: UITextView) -> Bool {
        return false
    }
}

extension PrivacyAndTermsVC: TitleNavigationBarDelegate {
    func titleNavBarBackBtnPressed() {
        self.navigationController?.popViewController(animated: true)
    }
}
