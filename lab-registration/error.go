package main

import (
	"fmt"
)

const (
	// Error codes
	ErrMacNotFound     = 1
	ErrUserNotFound    = 2
	ErrInternal	   = 666
)

// The serializable Error structure.
type Error struct {
	Code    int      `json:"code"`
	Message string   `json:"message"`
}

func (e *Error) Error() string {
	return fmt.Sprintf("[%d] %s", e.Code, e.Message)
}

// NewError creates an error instance with the specified code and message.
func NewError(code int, msg string) *Error {
	return &Error{
		Code:    code,
		Message: msg,
	}
}
