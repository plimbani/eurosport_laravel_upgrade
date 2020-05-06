//
//  AppleSignInData.swift
//  ESR
//
//  Created by Pratik Patel on 01/05/20.
//

import Foundation

struct AppleSignInData: Codable {
  
  var firstName: String
  var lastName: String
  var email: String
  var userId: String
  
  init() {
    self.firstName = ""
    self.lastName = ""
    self.email = ""
    self.userId = ""
  }
  
  init(firstName: String, lastName: String, email: String, userId: String) {
    self.firstName = firstName
    self.lastName = lastName
    self.email = email
    self.userId = userId
  }
}
