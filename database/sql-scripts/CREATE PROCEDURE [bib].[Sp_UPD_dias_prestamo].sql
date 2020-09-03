-- ================================================
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [bib].[Sp_UPD_dias_prestamo]
	  @_iDiasPrestamoId INTEGER,
	  @_cDescriDiasPrestamos VARCHAR(15),
      @_cTipo_Persona VARCHAR(15),
      @_iNumeroDias INTEGER,
      @_bHabilitado BIT,

      @_cUsuarioSis VARCHAR(50),
      @_cEquipoSis VARCHAR(50),
      @_cIpSis VARCHAR(15),
      @_cMacNicSis VARCHAR(35)

AS
BEGIN
	SET NOCOUNT ON;
	DECLARE @cUsuarioSis VARCHAR(50)
	SELECT @cUsuarioSis=c.cCredUsuario FROM seg.credenciales AS c WHERE c.iCredId=@_cUsuarioSis	
	IF @@ERROR<>0 GOTO ErrorCapturado

	UPDATE bib.dias_prestamo
	SET 
		cDescriDiasPrestamos=@_cDescriDiasPrestamos,
		cTipo_Persona=@_cTipo_Persona,
		iNumeroDias=@_iNumeroDias,
		bHabilitado=@_bHabilitado,

		cUsuarioSis=@_cUsuarioSis,
		dFechaSis=GETDATE(),
		cEquipoSis=@_cEquipoSis,
		cIpSis=@_cIpSis,
		cOpenUsr='E',
		cMacNicSis=@_cMacNicSis
	
	WHERE iDiasPrestamoId=@_iDiasPrestamoId
	IF @@ERROR<>0 GOTO ErrorCapturado
	
	COMMIT TRANSACTION
    SELECT 1 AS iResult
	RETURN 1
ErrorCapturado:
	ROLLBACK TRANSACTION
    SELECT 0 AS iResult
	RETURN 0
END
GO
